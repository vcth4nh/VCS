#!/usr/bin/env bash

gcc mypasswd.c -o mypasswd -lcrypt && sudo chown root ./mypasswd && sudo chmod 4701 ./mypasswd && ./mypasswd
