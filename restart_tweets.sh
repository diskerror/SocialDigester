#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

${DIR}/run tweets stop

${DIR}/run admin index

sleep 1
${DIR}/run tweets get &
