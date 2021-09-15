#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

"${DIR}/run" tweets stop

sleep 1

"${DIR}/run" tweets get > /dev/null 2>&1 &
