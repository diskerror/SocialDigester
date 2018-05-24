#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

RUNNING=$(${DIR}/cli.php tweets running);

if [ "$RUNNING" -eq 0 ]
then
    ${DIR}/restart_tweets.sh
fi
