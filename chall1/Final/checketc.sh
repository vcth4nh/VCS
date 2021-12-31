#!/usr/bin/env bash

directory="/etc/"
old_log="./checketc_old.tmp"
touch old_log
function check_modify {
	printf "//////////////// Modified Files ////////////////\n"
	local found=0
	while read -r file; do
		if [[ $file != '' ]]; then
			found=1
			printf "${file}\n"
		fi
	done <<<$(find ${directory} -type f -cmin -30 2>/dev/null)

	[[ "${found}" -eq 0 ]] && echo "No file was modified in the last 30 min."
}

function contain {
	local a b="$1"
	shift
	for a; do
		[[ "$a" == "$b" ]] && return 1

	done
	return 0
}

shopt -s nullglob

function check_create_delete {
	local curfiles=(${directory}*) deleted=() oldfiles
	mapfile -t oldfiles <$old_log

	diff_=("${curfiles[@]}" "${oldfiles[@]}")
	readarray -t diff < <(printf "%s\n" "${diff_[@]}" | sort | uniq -u)
	printf "%s\n" "${diff[@]}"

	local found_create=0 found_delete=0 is_new is_old

	printf "\n//////////////// Created Files ////////////////\n"
	for file in "${diff[@]}"; do
		contain "$file" "${curfiles[@]}"
		is_new=$?
		contain "$file" "${oldfiles[@]}"
		is_old=$?
		if [[ $is_new -eq 1 ]]; then
			found_create=1
			if [[ ${file: -4} == '.txt' ]]; then
				printf "\n$file\n---------BEGIN FILE CONTENT---------\n"
				cat "$file"
				printf "\n---------END FILE CONTENT---------\n\n"
			else
				printf "\n$file\n---------BEGIN FILE INFOMATION---------\n"
				ls -la "$file"
				stat "$file" | head -c -1
				printf "\n---------END FILE INFOMATION---------\n\n"
			fi
		elif [[ $is_old -eq 1 ]]; then
			found_delete=1
			deleted+=("${file}")
		fi
	done
	if [[ $found_create -eq 0 ]]; then
		echo "No file was created after the last check."
	fi

	printf "\n//////////////// Deleted Files ////////////////\n"
	if [[ $found_delete -eq 1 ]]; then
		for file in "${deleted[@]}"; do
			printf "$file\n"
		done
	else
		echo "No file was deleted after the last check"
	fi
	printf "%s\n" "${curfiles[@]}" >$old_log
}

echo $(date)
check_modify
check_create_delete
printf "\n\n\n"
