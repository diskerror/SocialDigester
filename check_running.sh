#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

RUNNING=$(${DIR}/run admin rate);

if [ "$RUNNING" -eq 0 ]
then
    ${DIR}/restart_tweets.sh
fi
