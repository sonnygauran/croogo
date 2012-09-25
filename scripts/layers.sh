#!/bin/bash

base="$(pwd)"

clear
if [[ $base == *scripts* ]]; then
	echo "Update your layers through the ./update script"
	exit
fi

path="$base/data/layers"
output="$base/views/themed/weatherph/webroot/img/layers"
echo "path $path";
echo "output $output";

if [ ! -d $path ]; then
	mkdir $path;
else
	echo "existing directory: $path";
fi
	echo "\n scp on txn2; layer images";
	scp -r -P 2215 netuser@199.195.193.240:/data/layers/live/* $path

	rm $output
if [ ! -h $output ]; then
	ln -s $path $output
else
	echo "soft link was created on $output";
fi
