found="False"

while read -r file; do
	found="True"
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
done <<<$(find ./VCS/test/ -type f -cmin -5 2>/dev/null)

[[ "${found}" == "False" ]] && echo "No file was created in the last 30 min."

# tim hieu <<< vs << vs <, [] vs [[]]
# tai sao de $cmd=$(find /home -type f -cmin -5 2>/dev/null) xong
# while << $cmd khong duoc
#tim hieu globbing
