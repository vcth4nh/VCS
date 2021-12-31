#!/usr/bin/env bash

old_log="./monitorssh_log.tmp"
touch $old_log

readarray -t cur_who < <(who)
mapfile -t old_who <$old_log

diff_=("${cur_who[@]}" "${old_who[@]}")
readarray -t diff < <(printf "%s\n" "${diff_[@]}" | sort | uniq -u)

echo ${diff[@]}
sendmail root@localhost $(printf "%s\n" "${diff[@]}")

printf "%s\n" "${cur_who[@]}" >$old_log
