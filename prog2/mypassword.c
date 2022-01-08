#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include <unistd.h>
#include <pwd.h>
#include <shadow.h>

#define SHADOW_TMP "/etc/shadow_mypasswd.tmp"

int replace_shadow(char *user_name, FILE *shadow_tmp);

int check_passwd(char *cur_hashed_passwd);

char *ran_setting();

void replace_sp_pwdp(struct spwd *spwd);


int main(void) {
    struct passwd *passwd = getpwuid(getuid());
    if (!passwd) {
        printf("Can't retrieve passwd information");
        return 1;
    }

    char *user_name = passwd->pw_name;
    printf("User name: %s\n", user_name);

    FILE *shadow_tmp = fopen(SHADOW_TMP, "w");
    if (!shadow_tmp) {
        printf("Can't access create %s\n", SHADOW_TMP);
        return 1;
    }

    int success = replace_shadow(user_name, shadow_tmp);
    fclose(shadow_tmp);


    if (success) {
        // replace original shadow file by new shadow file
        remove(SHADOW);
        rename(SHADOW_TMP, SHADOW);
        printf("\n----Done----\n");
    } else {
        //remove tmp file
        remove(SHADOW_TMP);
        printf("Unable to change the password\n");
        printf("\n----Failed----\n");
    }
}


char *ran_setting() {
    char alphabet[] = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789/.";
    srand(time(0));
    int salt_length = 10 + rand() % 10; // get random length of salt
    char *setting = (char *) malloc(sizeof(char) * (salt_length + 4));
    strcpy(setting, "$6$");

    // append random character (salt) to setting
    for (int i = 0; i < salt_length; i++) {
        setting[i + 3] = alphabet[rand() % strlen(alphabet)];
    }

    setting[salt_length + 3] = '\0';
    return setting;
}


int replace_shadow(char *user_name, FILE *shadow_tmp) {
    struct spwd *spwd;
    int success = 0;

    // copy line-by-line from shadow to temp shadow file
    while ((spwd = getspent())) {
        //check if this is the desired username
        if (strcmp(spwd->sp_namp, user_name) == 0) {
            if (!check_passwd(spwd->sp_pwdp)) {
                printf("Wrong password\n");
                return 0;
            }
            replace_sp_pwdp(spwd);
            putspent(spwd, shadow_tmp);
            success = 1;
        } else {
            putspent(spwd, shadow_tmp);
        }
    }
    endspent();

    return success;
}

void replace_sp_pwdp(struct spwd *spwd) {
    char new_passwd[265];
    printf("Enter new password: ");
    scanf("%s", new_passwd);

    // setting = "$6$*random salt*"
    char *setting = ran_setting();

    char *new_hashed_passwd = crypt(new_passwd, setting);
    spwd->sp_pwdp = new_hashed_passwd;
    free(setting);
}

int check_passwd(char *cur_hashed_passwd) {
    char cur_passwd[265];
    printf("Current password: ");
    scanf("%s", cur_passwd);

    // take position of 3rd '$' in cur_hashed_passwd
    char *last_pos;
    last_pos = strstr(cur_hashed_passwd, "$");
    last_pos = strstr(++last_pos, "$");
    last_pos = strstr(++last_pos, "$");

    // copy substring of cur_hashed_passwd
    // from index 0 to last_pos-1 to setting
    char *setting;
    long setting_len = last_pos - cur_hashed_passwd;
    setting = (char *) malloc(sizeof(char) * (setting_len + 1));
    strncpy(setting, cur_hashed_passwd, setting_len);
    setting[setting_len] = '\0';

    // get hashed password similar to which in shadow
    char *hashed_passwd = crypt(cur_passwd, setting);
    free(setting);

    if (strcmp(hashed_passwd, cur_hashed_passwd) == 0)
        return 1;
    else
        return 0;
}