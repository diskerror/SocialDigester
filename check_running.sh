#!/usr/bin/env bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

RUNNING=$("${DIR}/run" tweets running);

if [ $RUNNING -gt 10 ]
then
    "${DIR}/restart_tweets.sh"
fi
