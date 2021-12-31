directory="./test/*"

function contain() {
	local a b=$1
	shift
	for a; do
		[[ $a == $b ]] && echo 1 && return
	done
	echo 0
}

shopt -s nullglob
oldfiles=()
function check_create_delete {
	local curfiles=(${directory}) deleted=()
	local diff=$(echo ${curfiles[@]} ${oldfiles[@]} | tr ' ' '\n' | sort | uniq -u)
	local found_create=0 found_delete=0
	printf "\n\ncurfiles: ${curfiles[*]}\n\noldfile: ${oldfiles[*]}\n\ndiff: ${diff[*]}"
	# printf "\ndiff: ${diff[@]}"
	printf "\n//////////////// Created Files ////////////////\n"
	for file in "${diff[@]}"; do
		local check=$(contain $file "$curfiles")
		# printf "check: $check"
		if [[ $check -eq 1 ]]; then
			found_create=1
			if [[ ${file: -4} == '.txt' ]]; then
				printf "\n$file\n---------BEGIN FILE CONTENT---------\n"
				cat $file
				printf "\n---------END FILE CONTENT---------\n\n"
			else
				printf "\n$file\n---------BEGIN FILE INFOMATION---------\n"
				ls -la "$file"
				stat "$file" | head -c -1
				printf "\n---------END FILE INFOMATION---------\n\n"
			fi
		else
			found_delete=1
			deleted+=$files
		fi
	done

	if [[ $found_create -eq 0 ]]; then
		echo "No file was created after the last check."
	fi

	printf "\n//////////////// Deleted Files ////////////////\n"

	if [[ $found_delete -eq 1 ]]; then
		echo "del"
		for file in $deleted; do
			printf "$file\n"
		done
	else
		echo "No file was deleted after the last check"
	fi

	local index indices=${!curfiles[*]}
	oldfiles=()
	for index in $indices; do
		oldfiles[$index]=${curfiles[$index]}
	done

}
while [[ condition ]]; do
	echo $(pwd)
	sleep 01
	check_create_delete
	sleep 5
	clear
done
