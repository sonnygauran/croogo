#!/bin/sh

VERSION=$1

        cd Work/weather.com.ph/code
        ./code.sh
        cd weather.com.ph.git
        git checkout -b alpha/$VERSION origin/alpha/$VERSION
        git fetch
        git checkout development
        cd ..
        ./release.sh alpha.$VERSION
        scp -P 2215 alpha.$VERSION.zip netuser@199.195.193.240:/data/code


