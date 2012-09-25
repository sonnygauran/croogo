#!/bin/sh

type=$1


if [ "$type" = "" ]; then
clear
    echo "usage: ./update.sh [keyword]\n"
    echo "~> Keywords\n"
    echo "\tstations \tInserts new stations to your local database";
    echo "\treadings  \tUpdate readings";
    echo "\tdmo \t\tUpdates dmo";
    echo "\tmovies \t\tGet the latest movies for your local copy";
    echo "\tlayers \t\tDownload the latest layers from TXN2";
    echo "\tall \t\tUpdates all";
    echo "\n"

	read -p "Choose keyword [all]: " type
fi

if [ "$type" = "" ]; then
	type="all"
fi

if [ "$type" = 'stations' ]; then
	./Vendor/cake/cake/console/cake -app `pwd` meteomedia station_generate
	./Vendor/cake/cake/console/cake -app `pwd` meteomedia station_import	


elif [ "$type" = 'readings' ]; then
	./Vendor/cake/cake/console/cake -app `pwd` meteomedia station_readings
	./Vendor/cake/cake/console/cake -app `pwd` meteomedia readings_import


elif [ "$type" = 'dmo' ]; then
	echo "`date` :: Script Started."
	./scripts/dmoDownload.sh
	echo "`date` :: Script Finished."

elif [ "$type" = 'movies' ]; then
    echo "`date` :: Script Started."
    ./scripts/getMovies.sh
    echo "`date` :: Script Finished."

elif [ "$type" = 'all' ]; then

	echo "`date`:: Script Started."

	./Vendor/cake/cake/console/cake -app `pwd` meteomedia station_generate
	./Vendor/cake/cake/console/cake -app `pwd` meteomedia station_import
	./Vendor/cake/cake/console/cake -app `pwd` meteomedia station_readings
	./Vendor/cake/cake/console/cake -app `pwd` meteomedia readings_import
	./scripts/getMovies.sh
	./scripts/dmoDownload.sh

	echo "`date`:: Script Finished."

elif [ "$type" = 'layers' ]; then

	echo "`date`:: Scrpt Started."
	./scripts/layers.sh
	echo "`date`:: Scrpt FInished."

elif [ $type = "help" ]; then
	
	clear
	echo "usage: ./update.sh [keyword]\n"
	echo "~> Keywords\n"
    echo "\tstations \tInserts new stations to your local database";
    echo "\treadings  \tUpdate readings";
    echo "\tdmo \t\tUpdates dmo";
    echo "\tmovies \t\tGet the latest movies for your local copy";
    echo "\tlayers \t\tDownload the latest layers from TXN2";
    echo "\tall \t\tUpdates all";

	echo "\n"

else
clear
    echo "usage: ./update.sh [keyword]\n"
    echo "~> Keywords\n"
    echo "\tstations \tInserts new stations to your local database";
    echo "\treadings  \tUpdate readings";
    echo "\tdmo \t\tUpdates dmo";
    echo "\tmovies \t\tGet the latest movies for your local copy";
    echo "\tlayers \t\tDownload the latest layers from TXN2";
    echo "\tall \t\tUpdates all";    
	echo "\n"

fi


