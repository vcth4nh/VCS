#!/usr/bin/env bash

old_log="./monitorssh_log.tmp"
touch $old_log

readarray -t cur_who < <(who)
echo ${cur_who[@]}
mapfile -t old_who <$old_log
echo ${old_who[@]}

diff_=("${cur_who[@]}" "${old_who[@]}")

readarray -t diff < <(printf "%s\n" "${diff_[@]}" | uniq -u)

echo ${diff[@]}
sendmail

printf "%s\n" "${cur_who[@]}" >$old_log
