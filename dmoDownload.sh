#!/bin/sh

DATE=`date +%Y%m%d`

scp netuser@199.195.193.240:data/dmo/live/dmo$DATE.zip data/dmo/
unzip data/dmo/dmo$DATE.zip -d data/dmo/ *.csv
rm data/dmo/dmo$DATE.zip
