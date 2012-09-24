#!/bin/bash

#cp -r -P 2215 -r netuser@199.195.193.240:/data/layers/live/* .
cd ..
base="$(pwd)"
path="$base/data/layers"
output="$base/views/themed/weatherph/webroot/img/layers"

if [ ! -d $path ]; then
	mkdir $path;
else
	echo "existing directory: $path";
fi
	echo "\n scp on txn2; layer images";
	scp -r -P 2215 -r netuser@199.195.193.240:/data/layers/live/* $path

if [ ! -h $output ]; then
	ln -s $path $output
else
	echo "soft link was created on $output";
fi
