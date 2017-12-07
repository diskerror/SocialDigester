#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

PID_PATH=$(${DIR}/cli.php get pidPath);

${DIR}/cli.php tweets stop
sleep 1
if [ ! -e "$PID_PATH" ]
then
    mkdir -pm 777 "$PID_PATH"
fi
${DIR}/cli.php tweets get &
