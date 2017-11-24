#!/bin/sh

/var/www/html/run stop tweets
sleep 1
if [ ! -e /var/run/harvest ]
then
    mkdir -pm 777 /var/run/harvest
fi
/var/www/html/run get tweets &
