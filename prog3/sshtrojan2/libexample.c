#include <stdio.h>
#include <unistd.h>
#include <string.h>
#include <dlfcn.h>
// #define _GNU_SOURCE

int puts(const char *message) {
    int (*new_puts)(const char *message);
    int result;
    new_puts = dlsym(RTLD_NEXT, "puts");
    if (strcmp(message, "Hello world!n") == 0) {
        result = new_puts("Goodbye, cruel world!n");
    } else {
        result = new_puts(message);
    }
    return result;
}