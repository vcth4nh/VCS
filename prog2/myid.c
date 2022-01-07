#include <stdio.h>
#include <stdlib.h>
#include <pwd.h>
#include <grp.h>
#include <string.h>

#include <malloc.h>

char *groupNameFromUId(char *user_name, gid_t user_gid) {
    char *primary_group = getgrgid(user_gid)->gr_name;

    // init all_group with primary group name
    char *all_group = strdup(primary_group);

    // loop through all groups in group file
    struct group *grp;
    while ((grp = getgrent())) {
        char *found_name = NULL;
        char **gr_uname = grp->gr_mem;

        // loop to search for user_name in group's username
        for (; *gr_uname != NULL; gr_uname++) {
            if (!strcmp(*gr_uname, user_name)) {
                found_name = grp->gr_name;
                break;
            }
        }

        // append group name if found user_name in group's username
        if (found_name && found_name[0] != '\0') {
            all_group = (char *) realloc(all_group, strlen(all_group) + strlen(found_name) + 3);
            strcat(all_group, "; ");
            strcat(all_group, found_name);
        }
    }
    endgrent();
    all_group[strlen(all_group)] = '\0';

    return all_group;
}


int main(void) {
    char user_name[32];
    printf("User name: ");
    scanf("%s", user_name);

    struct passwd *pwd;
    pwd = getpwnam(user_name);
    if (!pwd) {
        printf("No such user: %s\n", user_name);
        return 1;
    }

    printf("-------------Result-------------\n");
    printf("User id: %d\n", pwd->pw_uid);
    printf("User name: %s\n", pwd->pw_name);
    printf("Home folder: %s\n", pwd->pw_dir);
    char *all_group = groupNameFromUId(pwd->pw_name, pwd->pw_gid);
    printf("User's group: %s\n", all_group);
    free(all_group);
}