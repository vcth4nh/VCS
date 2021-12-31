# found=0

# while read -r file; do
# 	found=1
# 	printf "$file\n"
# done <<<$(find /home/Documents/VCS/ -type f -cmin -1 2>/dev/null)

# [[ "${found}" -eq 1 ]] && echo "No file was modified in the last 30 min."

function check_modify {
	printf "\n//////////////// Modified Files ////////////////\n"
	local found=()
	while read -r file; do
		if [[ $file != '' ]]; then
			found=1
			printf "${file}\n"
		fi
		# ls -la "~/Document/VCS/test/"
	done <<<$(find ./test -type f -cmin -1 2>/dev/null)

	[[ ${#found} -eq 0 ]] && echo "No file was modified in the last 30 min."

}

while [[ 1 ]]; do
	check_modify
	sleep 2
done
