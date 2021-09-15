#!/bin/zsh

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

"${DIR}/run" admin indexDb

"${DIR}/restart_tweets.sh"
