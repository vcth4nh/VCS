#!/usr/bin/env bash


touch oldList.txt newList.txt mail.txt

who > newList.txt
while read -r line
do
	check=$(grep -w "$line" -m 1 oldList.txt) 
	if [ ! $check ]
	then
		echo -e "User" "$(echo "$line" | awk '{print $1}')" "dang nhap thanh cong vao thoi gian" "$(echo "$line" | awk '{print $4}') $(echo "$line" | awk '{print $3}')" > mail.txt
	fi
done < newList.txt
$(sendmail root@localhost mail.txt)
cat newList.txt > oldList.txt 