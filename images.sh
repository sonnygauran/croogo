#!/bin/bash

#DATE="$1"
DATE=`(date +%Y%m%d-%H:%M:%S)`
DATE2=`(date +%Y%m%d%H%M%S)`
TO="`pwd`/data/images/"

#pilipinas [111.32714843750325, 0.8402895756535752, 135.67285156249676, 24.41201768480203]
COORDS="&x1=111.32714843750325&x2=135.67285156249676&y2=24.41201768480203&y1=0.8402895756535625"
COORDS_LZN="&x1=116.1635742875163&x2=128.33642578124838&y2=22.582414770293372&y1=10.950736511266356"
#luzon [116.16357421875163, 10.950736511266356, 128.33642578124838, 22.582414770293372]
COORDS_VZMD="&x1=119.12957421875164&x2=131.3024257812484&y2=15.627060843031257&y1=3.6491697643203027"
#visayas/mindanao [119.12957421875164, 3.6491697643203027, 131.3024257812484, 15.627060843031257]
COORDS_PLSLU="&x1=114.53357421875162&x2=126.70642578124838&y2=14.56078202759215&y1=2.5464027989479354"
#palawan/sulu  114.53357421875162, 2.5464027989479354, 126.70642578124838, 14.56078202759215 

# 3 more using "$('#map').geomap('option', 'bbox');"

#SATELLITE_VIS="http://wetter4.meteomedia.ch/?q=sve&a=image&x1=111.32714843750325&x2=135.67285156249676&y1=0.8402895756535625&y2=24.41201768480203"
#ATELLITE_VIS="http://alpha.meteomedia-portal.com/services/wetter4.php?dt=20120712120000&q=sve&a=image&"
#SATELLITE_VIS="http://alpha.meteomedia-portal.com/services/wetter4.php?dt=20120712120000&api_key=portal-efd339395c80ad957acb695bb9758399&q=sve&leg=nil&a=image&x=554&y=554&srs=EPSG:900913&"
SATELLITE_VIS="http://alpha.meteomedia-portal.com/services/wetter4.php?dt=20120712170000&api_key=portal-efd339395c80ad957acb695bb9758399&q=sve&leg=nil&a=image&x=554&y=554&srs=EPSG:900913&"

TEMP="http://alpha.meteomedia-portal.com/services/wetter4.php?dt=20120712120000&api_key=portal-efd339395c80ad957acb695bb9758399&q=meh_ifm&leg=nil&a=image&x=554&y=554&srs=EPSG:900913&"
PRESSURE="http://alpha.meteomedia-portal.com/services/wetter4.php?dt=20120712120000&api_key=portal-efd339395c80ad957acb695bb9758399&q=meh_ifm&leg=nil&a=image&x=554&y=554&srs=EPSG:900913&p=QFF&"
wget -O $DATE.satellite_vis.png "$SATELLITE_VIS$COORDS"

echo "Done with satellite_vis"
wget -O "${TO}${DATE}.temperature_Philippines.png" "$TEMP$COORDS"
wget -O "${TO}${DATE}.temperature_Luzon.png" "$TEMP$COORDS_LZN"
wget -O "${TO}${DATE}.temperature_VisayasMindanao.png" "$TEMP$COORDS_VZMD"
wget -O "${TO}${DATE}.temperature_PalawanSulu.png" "$TEMP$COORDS_PLSLU"
echo "Done with Temperature"
wget -O "${TO}${DATE}.pressure_Philippines.png" "$PRESSURE$COORDS"
wget -O "${TO}${DATE}.pressure_Luzon.png" "$PRESSURE$COORDS_LZN"
wget -O "${TO}${DATE}.pressure_VisayasMindanao.png" "$PRESSURE$COORDS_VZMD"
wget -O "${TO}${DATE}.pressure_PalawanSulu.png" "$PRESSURE$COORDS_PLSLU"
echo "Done with Pressure"

echo $DATE2
#wget -O pressureB.png "$PRESSURE$COORDS"
