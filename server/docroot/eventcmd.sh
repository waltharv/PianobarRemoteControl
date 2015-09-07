#!/bin/bash
docroot="/path/to/server/docroot"
action="$1"

cat /dev/stdin >| "$docroot"/songinfo
echo "action=$action" >> "$docroot"/songinfo

