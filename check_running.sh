#!/bin/zsh

#DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
#
#RUNNING=$(${DIR}/run tweets running);
#
#if [ "$RUNNING" -eq 0 ]
#then
#    ${DIR}/restart_tweets.sh
#fi

RUNNING=$(/var/www/politicator/run tweets running);

if [ "$RUNNING" -eq 0 ]
then
    /var/www/politicator/restart_tweets.sh
fi
