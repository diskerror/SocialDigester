#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

${DIR}/cli.php tweets stop

PID_PATH=$(${DIR}/cli.php get pidPath);
if [ ! -e "$PID_PATH" ]
then
    mkdir -pm 777 "$PID_PATH"
fi

CACHE_PATH=$(${DIR}/cli.php get cachePath);
if [ ! -e "$CACHE_PATH" ]
then
    mkdir -pm 777 "$CACHE_PATH"
fi

sleep 1
${DIR}/cli.php tweets get &
