#!/bin/sh


VERSION=$1
       
	sudo rm -rf /var/www/html
	sudo mkdir /var/www/html
	sudo chown netuser:netuser /var/www/html
	cp /data/code/live.$VERSION.zip .
	unzip -d /var/www/html live.$VERSION.zip
	cd /var/www
	pwd
	sudo sh -x ./update.sh live.$VERSION
	sudo cp ~netuser/database.php html/config/database.php
        sudo cp ~netuser/settings.private.yml html/config/
	sudo chown netuser:netuser html/config/database.php	
	sudo git clone git@github.com:sonnygauran/cakephp.git html/Vendor/cake
	sh -x /home/netuser/symlink.sh
