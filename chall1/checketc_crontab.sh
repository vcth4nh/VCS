sudo crontab -l >./cron.tmp
sudo rm ./checketc_old.tmp
sudo echo "*/1 * * * * $(pwd)/checketc.sh >> /var/log/checketc.log" >>./cron.tmp
sudo crontab ./cron.tmp
sudo rm ./cron.tmp
