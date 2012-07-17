#!/bin/sh

echo "`date`:: Script Started."

./Vendor/cake/cake/console/cake -app `pwd` meteomedia station_generate
./Vendor/cake/cake/console/cake -app `pwd` meteomedia station_import
./Vendor/cake/cake/console/cake -app `pwd` meteomedia station_readings
./Vendor/cake/cake/console/cake -app `pwd` meteomedia readings_import
./getMovies.sh
./dmoDownload.sh

echo "`date`:: Script Finished."
