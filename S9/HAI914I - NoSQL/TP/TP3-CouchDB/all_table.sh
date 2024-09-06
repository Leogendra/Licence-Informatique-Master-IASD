#!/bin/bash
while true
do
  curl -X GET $COUCH3/_all_dbs
  echo "\n"
  sleep 2
done