#include <stdio.h>
#include <shadow.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include <crypt.h>

#if 0
struct passwd {
        char *pw_name; /* Login name (username) */
        char *pw_passwd; /* Encrypted password */
        uid_t pw_uid; /* User ID */
        gid_t pw_gid; /* Group ID */
        char *pw_gecos; /* Comment (user information) */
        char *pw_dir; /* Initial working (home) directory */
        char *pw_shell; /* Login shell */
    };
#endif

#define SHADOW_TMP "/etc/shadow_mypasswd.tmp"

char *ran_salt() {
    char alphabet[] = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789/.";
    srand(time(0));
    int salt_length = 10 + rand() % 10;
    char *salt = (char *) malloc(sizeof(char) * (salt_length + 1));
    if (salt == NULL) {
        printf("Malloc memory error\n");
        exit(1);
    }
    salt[salt_length] = '\0';
    for (int i = 0; i < salt_length; i++) {
        salt[i] = alphabet[rand() % strlen(alphabet)];
    }
    return salt;
}

//void replace_sp_pwdp(struct spwd *spwd) {
//    char new_passwd[265];
//    printf("\n\n------------Change password-------------\n");
//    printf("Enter new password: ");
//    scanf("%s", new_passwd);
//    char salt[] = "$6$";
//    strcat(salt, ran_salt());
//    char *new_hashed_passwd = crypt(new_passwd, salt);
//    spwd->sp_pwdp = new_hashed_passwd;
//    printf("New spwd: %s\n\n\n", spwd->sp_pwdp);
//}

void replace_shadow(struct spwd *spwd_new, FILE *tmp_shadow) {
    struct spwd *spwd;
    while ((spwd = getspent())) {
        if (strcmp(spwd->sp_namp, spwd_new->sp_namp) == 0) {
            putspent(spwd_new, tmp_shadow);
        } else {
            putspent(spwd, tmp_shadow);
        }
    }
    remove(SHADOW);
    rename(SHADOW_TMP, SHADOW);
}

int main(void) {

    struct spwd *spwd;
    char user_name[36];

    printf("User name: ");
    scanf("%s", user_name);
    spwd = getspnam(user_name);
    if (!spwd) {
        printf("No user with name %s\n", user_name);
    } else {
        printf("-------------Result-------------\n");
        printf("Current user: %s\n", spwd->sp_namp);
        printf("Current password: %s\n", spwd->sp_pwdp);

        char new_passwd[265];
        printf("\n\n------------Change password-------------\n");
        printf("Enter new password: ");
        scanf("%s", new_passwd);
        char salt[] = "$6$";
        char *new_hashed_passwd = crypt(new_passwd, salt);
        spwd->sp_pwdp = new_hashed_passwd;
        printf("New spwd: %s\n\n\n", spwd->sp_pwdp);

        replace_shadow(spwd, fopen(SHADOW_TMP, "w"));
    }
}