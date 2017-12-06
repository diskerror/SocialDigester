#!/bin/bash

PID_PATH=$(/var/www/html/cli.php get processPath);

/var/www/html/cli.php tweets stop
sleep 1
if [ ! -e "$PID_PATH" ]
then
    mkdir -pm 777 "$PID_PATH"
fi
/var/www/html/cli.php tweets get &
