#!/bin/sh

/var/www/html/cli.php tweets stop
sleep 1
if [ ! -e /var/run/politicator ]
then
    mkdir -pm 777 /var/run/politicator
fi
/var/www/html/cli.php tweets get &
