#!/bin/sh

VERSION=$1


		sudo rm -rf /var/www/html
		sudo mkdir /var/www/html
		sudo chown netuser:netuser /var/www/html
        cp /data/code/alpha.$VERSION.zip .
        unzip -d /var/www/html alpha.$VERSION.zip
        cd /var/www/html
        pwd
        sudo sh -x ./update.sh alpha.$VERSION
        sudo cp ~netuser/database.php html/config/database.php
        sudo cp ~netuser/settings.private.yml html/config/
        sudo chown netuser:netuser html/config/database.php
		sudo git clone git@github.com:sonnygauran/cakephp.git html/Vendor/cake
~                                                            
