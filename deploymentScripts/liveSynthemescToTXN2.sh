#!/bin/sh

VERSION=$1

        cd Work/weather.com.ph/code
        ./code.sh
        cd weather.com.ph.git
        git checkout -b live/$VERSION origin/live/$VERSION
        git fetch
        git checkout development
        cd ..
        ./release.sh live.$VERSION
        scp -P 2215 live.$VERSION.zip netuser@199.195.193.240:/data/code


