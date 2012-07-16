#!/bin/bash

PATH="data/movies"

# Create directory if non existent
if [ ! -d $PATH ]; then
/bin/mkdir $PATH;
fi

cd $PATH

# Copy movies
/usr/bin/scp -r netuser@199.195.193.240:/data/movies/live/* .
cd ../..

# Create symlink
if [ ! -h "views/themed/weatherph/webroot/videos" ]; then
/bin/ln -s $PATH views/themed/weatherph/webroot/videos
fi
