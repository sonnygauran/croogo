#!/bin/sh

DATUM="`date +%Y%m%d`";


mv html "html_$DATUM";
mv -u "html_$DATUM/home/netuser/Work/weather.com.ph/code/$1" html;
rm -rf "html_$DATUM";

chown -R netuser:netuser html;
chmod -R 777 html/tmp/cache/;

