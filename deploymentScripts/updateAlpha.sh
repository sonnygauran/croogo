#!/bin/sh


VERSION=$1
       
	sudo rm -rf /var/www/html
	sudo mkdir /var/www/html
	sudo chown netuser:netuser /var/www/html
	cp /data/code/alpha.$VERSION.zip .
	unzip -d /var/www/html alpha.$VERSION.zip
	cd /var/www
	pwd
	sudo sh -x ./update.sh alpha.$VERSION
	sudo cp ~netuser/database.php html/config/database.php
        sudo cp ~netuser/settings.private.yml html/config/
	sudo chown netuser:netuser html/config/database.php	
	cp -r /data/code/cakephp.git html/Vendor/cake
	sh -x /home/netuser/symlink.sh
	cp /home/netuser/robots.txt /var/www/html/webroot/
