#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

${DIR}/run tweets stop

PID_PATH=$(${DIR}/run get pidPath);
if [ ! -e "$PID_PATH" ]
then
    mkdir -pm 777 "$PID_PATH"
fi

CACHE_PATH=$(${DIR}/run get cachePath);
if [ ! -e "$CACHE_PATH" ]
then
    mkdir -pm 777 "$CACHE_PATH"
fi

sleep 1
${DIR}/run tweets get > /dev/null 2>&1 &
