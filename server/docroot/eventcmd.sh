#!/bin/bash
docroot="/home/user/Dropbox/whinc/code/PianobarRemoteControl/server/docroot"
action="$1"

cat /dev/stdin >| "$docroot"/songinfo
echo "action=$action" >> "$docroot"/songinfo

