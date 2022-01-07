sudo crontab -l >./cron.tmp
sudo rm ./monitorssh_log.tmp
sudo echo "*/5 * * * * $(pwd)/monitorssh.sh" >>./cron.tmp
sudo crontab ./cron.tmp
sudo rm ./cron.tmp
