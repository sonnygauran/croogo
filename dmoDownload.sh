#!/bin/sh

DATE=`date +%Y%m%d`

if [ ! -d data/dmo ]; then
	echo "Creating directory at [`pwd`/data/dmo]"
	mkdir -p data/dmo
fi
scp -P 2215 netuser@199.195.193.240:/data/dmo/live/dmo$DATE.zip data/dmo/
unzip data/dmo/dmo$DATE.zip -d data/dmo/ *.csv
rm data/dmo/dmo$DATE.zip
