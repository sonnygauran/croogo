#!/bin/sh


VERSION=$1
IP='199.195.193.240'
PORT=`-P 2215`

#       cp /data/code/live.$VERSION.zip .
        scp -P 2215 netuser@199.195.193.240:/data/code/live.$VERSION.zip .
        unzip -d /var/www/html live.$VERSION.zip
cd /var/www/
pwd
sudo ./update.sh live.$VERSION
sudo cp ~netuser/database.php html/config/database.php
sudo cp ~netuser/settings.private.yml html/config/
sudo chown netuser:netuser html/config/database.php

