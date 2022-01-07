#!/usr/bin/env bash

gcc mypassword.c -o mypassword -Wall -lcrypt && sudo chown root: ./mypassword && sudo chmod 4711 ./mypassword && ./mypassword
