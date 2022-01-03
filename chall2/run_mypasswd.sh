#!/usr/bin/env bash

gcc mypasswd.c -o mypasswd -lcrypt && sudo chown root: ./mypasswd && sudo chmod 4711 ./mypasswd && ./mypasswd
