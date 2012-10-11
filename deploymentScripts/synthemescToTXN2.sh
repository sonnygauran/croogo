#!/bin/sh

SERVER="199.195.193.240"
DEPLOYMENT="alpha"

VERSION=$1

        cd ~/Work/weather.com.ph/code
        sh -x ./code.sh
        cd weather.com.ph.git
        git checkout -b $DEPLOYMENT/$VERSION origin/$DEPLOYMENT/$VERSION
        git fetch
        git checkout development
        cd ..
        sh -x ./release.sh $DEPLOYMENT.$VERSION
        scp -P 2215 $DEPLOYMENT.$VERSION.zip netuser@$SERVER:/data/code
