shopt -s nullglob
oldfiles=(*)
empty_diff=$(echo ${oldfiles[@]} ${oldfiles[@]} | tr ' ' '\n' | sort | uniq -u)

curfiles=(*)
curfiles=(${curfiles[@]} ${oldfiles[@]})
diff=$(echo ${curfiles[@]} ${oldfiles[@]} | tr ' ' '\n' | sort | uniq -u)
# echo ${curfiles[@]} ${oldfiles[@]} | tr ' ' '\n' | sort
echo "${diff[0]}"
# [[ ${#diff[@]} -eq 0 ]] && echo "No file was created after the last check."
echo ${#diff[@]}

indices=${!curfiles[*]}
for index in $indices; do
	oldfiles[$index]=${curfiles[$index]}
done
oldfiles="${curfiles[@]}"
