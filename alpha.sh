#!/bin/sh

COUNT=$#
BRANCH="origin";
CURRENT_BRANCH=$(git branch -a | grep '*' | cut -d ' ' -f 2)
NEW_VERSION=$1
CHECK_EXISTING=$(git branch -a | grep alpha/$NEW_VERSION | wc -l)


if [ $COUNT -eq  0 ]; then
	echo "Please enter a new version number\n\te.g. 9.9"
	exit
fi

if [ ! $CHECK_EXISTING -eq  0 ]; then
	echo -e "\e[1;32mVersion already exists [$NEW_VERSION]"
	git branch -a | grep alpha
	exit
fi

echo "\n`date`:: Script Started\n"

if [ $CURRENT_BRANCH = "development" ]; then
	
	git pull $BRANCH development
	git checkout -b $NEW_VERSION development
	git push $BRANCH alpha/$NEW_VERSION:alpha/$NEW_VERSION

fi

echo "\n`date`:: Script Finished\n"
