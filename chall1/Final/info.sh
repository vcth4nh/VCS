#!/usr/bin/env bash


delim="\n\n------------------------------------------\n\n"

echo "" >info.log

comp_name=$(hostname)
printf "1. Computer name: $comp_name$delim" >info.log

distr_name=$(lsb_release -d | awk '{printf "%s %s\n", $2, $3}')
printf "Distribution name and version: $distr_name$delim" >>info.log

os_ver=$(lsb_release -i | awk '{print $3}')
printf "2. OS version: $os_ver$delim" >>info.log

cpu_info=$(lscpu | grep 'Model name\|CPU op-mode\|CPU MHz\|CPU max MHz\|CPU min MHz')
printf "3. CPU information:\n$cpu_info$delim" >>info.log

ram_info=$(vmstat -s -S M | grep "total mem")
printf "4. Total main memory: $ram_info$delim" >>info.log

disk_info=$(df -B M -h / | awk '{print $4}' | tail -1)
printf "5. Available disk memory: $disk_info$delim" >>info.log

ip_list=$(hostname -I | awk '{printf "ipv4: %s\tipv6: %s", $1, $2}')
printf "6. Ip adress:\n$ip_list$delim" >>info.log

user_info=$(cut -d: -f1 /etc/passwd | sort | tr "\n" ", " | head --byte -1)
printf "7. All user: $user_info$delim" >>info.log

root_proc=$(ps -U root -u root -o cmd | sort | tr "\n" ", " | head --byte -1)
printf "8. Processes runing as root: $root_proc$delim" >>info.log

open_port=$(ss -tuln | awk '/LISTEN/ {print $5}' | awk -F ':' '{print $NF}' | sort -n | xargs)
printf "9. Currently opening port: $open_port$delim" >>info.log

writeable_folder=$(find / ! -type d -writable)
printf "10. Writeable folder for other user: $writeable_folder$delim" 

installed_pack=$(dpkg-query -W -f 'Packages name: ${package}\nVersion: ${version}\n-----------\n')
printf "11. Installed packages:\n$installed_pack$delim" >>info.log
