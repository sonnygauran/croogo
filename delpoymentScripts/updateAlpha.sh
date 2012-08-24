VERSION=$1


        cp /data/code/alpha.$VERSION.zip .
        unzip -d /var/www/html alpha.$VERSION.zip
        cd /var/www/
        pwd
        sudo ./update.sh alpha.$VERSION
        sudo cp ~netuser/database.php html/config/database.php
        sudo cp ~netuser/settings.private.yml html/config/
        sudo chown netuser:netuser html/config/database.php
~                                                            
