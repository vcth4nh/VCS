#include <stdio.h>
#include <pwd.h>
#include <grp.h>
#include <string.h>


char *groupNameFromUId(char *user_name, gid_t user_gid) {
    struct group *grp;
    char *all_group = NULL;
    char *tmp = getgrgid(user_gid)->gr_name;
    all_group = tmp;
    while ((grp = getgrent())) {
        char *found_name = NULL;
        char **gr_uname = grp->gr_mem;
        for (gr_uname; *gr_uname != NULL; gr_uname++) {
            if (!strcmp(*gr_uname, user_name)) {
                found_name = grp->gr_name;
                break;
            }
        }
        if (found_name && found_name[0] != '\0') {
            strcat(all_group, "; ");
            strcat(all_group, found_name);
        }
    }
    endgrent();
    return all_group;
}


int main(void) {
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

    struct group {
        char *gr_name; /* Group name */
        char *gr_passwd; /* Encrypted password (if not password shadowing) */
        gid_t gr_gid; /* Group ID */
        char **gr_mem; /* NULL-terminated array of pointers to names
			of members listed in /etc/group */
    };
#endif

    struct passwd *pwd;
    uid_t user_id;

    printf("User id: ");
    scanf("%d", &user_id);

    pwd = getpwuid(user_id);
    if (!pwd) {
        printf("No user with id %d",user_id);
    } else {
        printf("-------------Result-------------\n");
        printf("User id: %d\n", pwd->pw_uid);
        printf("User name: %s\n", pwd->pw_name);
        printf("Home folder: %s\n", pwd->pw_dir);
        printf("User's group: %s\n", groupNameFromUId(pwd->pw_name, pwd->pw_gid));
    }
}