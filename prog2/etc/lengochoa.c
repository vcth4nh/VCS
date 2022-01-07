#include <pwd.h>
#include <stddef.h>
#include <stdio.h>
#include <stdlib.h>


uid_t userIdFromName(const char *name) {
    struct passwd *pwd;
    uid_t u;
    char *endptr;
    if (name == NULL || *name == '\0')
        return -1;

    u = strtol(name, &endptr, 10);
    if (*endptr == '\0')
        return u;

    pwd = getpwnam(name);
    if (pwd == NULL)
        return -2;

    return pwd->pw_uid;
}


int main() {
    char *name=NULL;
    printf("user name: ");
    scanf("%s", name);

    printf("user id: %d\n", userIdFromName("user_test"));
    printf("user id: %d\n", userIdFromName(name));


    return 0;

}