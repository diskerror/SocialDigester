#!/bin/zsh

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

(${DIR}/run admin indexdb &)

PID_PATH=$(${DIR}/run get pidPath);
if [ ! -e "$PID_PATH" ]
then
    mkdir -pm 777 "$PID_PATH"
else
    chmod 777 "$PID_PATH"
fi

CACHE_PATH=$(${DIR}/run get cachePath);
if [ ! -e "$CACHE_PATH" ]
then
    mkdir -pm 777 "$CACHE_PATH"
else
    chmod 777 "$CACHE_PATH"
fi
