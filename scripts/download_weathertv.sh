#!/bin/bash

baseDirectory="$(pwd)/.."
dataDirectory="$baseDirectory/data/weathertv"
softLinkLocation="$baseDirectory/views/themed/weatherph/webroot/weathertv"
if [ ! -d $dataDirectory ]; then
	echo "creating the directory [$dataDirectory]";
	mkdir $dataDirectory;
fi

echo "moving to $dataDirectory"
cd $dataDirectory;

scp -r -P 2215 netuser@199.195.193.240:/data/weathertv/live/* .
count=$(ls | wc -l)

if [ $count != 0 ]; then
	echo "copied all the movies"
else
	echo "no files downloaded"
fi

if [ ! -d $softLinkLocation ]; then
	echo "creating soft link"
	ln -s $dataDirectory $softLinkLocation
fi
