#!/usr/bin/env bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Only restart if it was running.
if [[ $("${DIR}/run" admin pidExists) -eq 1 ]]
then
  "${DIR}/run" tweets stop

  sleep 1

  "${DIR}/run" tweets get > /dev/null 2>&1 &
fi
