#!/usr/bin/env bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

echo "stop receiving if running"
"${DIR}/run" tweets stop

echo "clear tweets"
"${DIR}/run" admin clearTweets

echo "set pid path"
PID_PATH=$("${DIR}/run" get pidPath);
if [ ! -e "$PID_PATH" ]
then
    mkdir -pm 777 "$PID_PATH"
else
    chmod 777 "$PID_PATH"
fi

echo "set cache path"
CACHE_PATH=$("${DIR}/run" get cachePath);
if [ ! -e "$CACHE_PATH" ]
then
    mkdir -pm 777 "$CACHE_PATH"
else
    chmod 777 "$CACHE_PATH"
fi

echo "index collections"
"${DIR}/run" admin indexDb

echo "starting consumtion"
"${DIR}/run" tweets startBg > /dev/null 2>&1
