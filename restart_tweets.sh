#!/bin/sh

/var/www/html/cli.php stop tweets
sleep 1
if [ ! -e /var/run/harvest ]
then
    mkdir -pm 777 /var/run/harvest
fi
/var/www/html/cli.php get tweets &
