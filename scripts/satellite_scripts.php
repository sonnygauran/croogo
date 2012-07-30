<?php
$location = "../views/themed/weatherph/webroot/img/layers/satellite_history/";

$res = "+1 hour +30 minutes";
$startdate = date('YmdHis');
$enddate = date('YmdHis', strtotime('+1 hour +30 minutes',strtotime($startdate)));
echo $startdate;
echo "\n$enddate";

$STATIC = array (
	'start' => date('YmdHis', strtotime($startdate)),
	'end' => date('YmdHis', strtotime($enddate)),
);

$start = date('YmdHis', strtotime($startdate));
$end = date('YmdHis', strtotime($enddate));
$counter =0;
$filetotal = 0;


$file_sizes = array(
    'luzon' => array(
        'pressure' => 0,
        'temperature' => 0,
    ),
    'visayas_mindanao' => array(
        'pressure' => 0,
        'temperature' => 0,
    ),
    'palawan_sulu' => array(
        'pressure' => 0,
        'temperature' => 0,
    ),
    'satellite' => 0,
);

$properties = array(
    'satellite' =>"http://alpha.meteomedia-portal.com/services/wetter4.php?dt=".$start."&api_key=portal-efd339395c80ad957acb695bb9758399&q=sve&leg=nil&a=image&x=554&y=554&srs=EPSG:900913&"
    );

$coordinates = array(
    'all' => "&x1=111.3134765625&x2=135.6591796875&y2=24.126701958681668&y1=0.5273363048115169",
    'luzon' =>"&x1=119.21264648437499&x2=125.299072265625&y2=19.694314241825747&y1=13.870080100685891",
    'visayas_mindanao' =>"&x1=122.16796875&x2=128.25439453125&y2=12.651058133703483&y1=6.653695352486294",
    'palawan_sulu' =>"&x1=116.54296874999999&x2=122.62939453125001&y2=12.39365886237742&y1=6.391730485481462",

);

function microtime_float() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float) $usec + (float) $sec);
}
$time_start = microtime_float();


foreach($coordinates as $coordinate_key => $coordinate_value){
    foreach($properties as $property_key => $property_value){
        while (strtotime($start) < strtotime($end)){

            $counter++;
            $start = date('YmdHis',strtotime($res, strtotime($start)));
            $url = "{$property_value}{$coordinate_value}";
            echo "\n $url \n";
            exec("wget -O '$location/$start{$coordinate_key}_{$property_key}.png' '$url'");
            $filetotal += filesize("$start {$coordinate_key}_{$property_key}.png");
            $file_mb = round(($filetotal / 1048576), 2);


        }
        $start = $STATIC['start'];
    }
}

echo "Profile: \n\n";
echo "Total file size: "."$file_mb MB \n";
echo "Total number of downloads: "."$counter\n";
echo "Total time accumulated: ";

$time_end = microtime_float();
$time = $time_end - $time_start;

echo "$time seconds\n";


/*
function microtime_float() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float) $usec + (float) $sec);
}
$time_start = microtime_float();

$end = date('YmdHis', strtotime($enddate));

$counter =0;

$filetotal = 0;

$properties = array(
'tempertature' =>   "http://alpha.meteomedia-portal.com/services/wetter4.php?dt=".$start."&api_ ey=portal-efd339395c80ad957acb695bb9758399&q=meh_ifm&leg=nil&a=image&x=554&y=554&srs=EPSG:900913&",
    'pressure' =>       "http://alpha.meteomedia-portal.com/services/wetter4.php?dt=".$start."&api_key=portal-efd339395c80ad957acb695bb9758399&q=meh_ifm&leg=nil&a=image&x=554&y=554&srs=EPSG:900913&p=QFF&",
    'satellite' =>      "http://alpha.meteomedia-portal.com/services/wetter4.php?dt=".$start."&api_key=portal-efd339395c80ad957acb695bb9758399&q=sve&leg=nil&a=image&x=554&y=554&srs=EPSG:900913&"
    );

$coordinates = array(
    'all' => "&x1=111.32714843750325&x2=135.67285156249676&y2=24.41201768480203&y1=0.8402895756535625",
    'luzon' =>"&x1=116.1635742875163&x2=128.33642578124838&y2=22.582414770293372&y1=10.950736511266356",
    'visayas_mindanao' =>"&x1=119.12957421875164&x2=131.3024257812484&y2=15.627060843031257&y1=3.6491697643203027",
    'palawan_sulu' =>"&x1=114.53357421875162&x2=126.70642578124838&y2=14.56078202759215&y1=2.5464027989479354",
dd
foreach($coordinates as $coordinate_key => $coordinate_value){
    foreach($properties as $property_key => $property_value){
        while (strtotime($start) < strtotime($end)){

            $counter++;
            $start = date('YmdHis',strtotime($res, strtotime($start)));
            $url = "{$property_value}{$coordinate_value}";
            echo "\n $url \n";
            exec("wget -O '$start {$coordinate_key}_{$property_key}.png' '$url'");
            $filetotal += filesize("$start {$coordinate_key}_{$property_key}.png");
            $file_mb = round(($filetotal / 1048576), 2);


        }
        $start = $STATIC['start'];
    }
}
$endscript = date('i:s');
$totalscript = date('i:s', strtotime($endscript) - strtotime($startscript));
echo "Profile: \n\n";
echo "Total file size: "."$file_mb MB \n";
echo "Total number of downloads: "."$counter\n";
$to_time = strtotime("$endscript");
$from_time = strtotime("$startscript");
echo "Total time accumulated: ";
$time_end = microtime_float();
$time = $time_end - $time_start;
echo "$time seconds\n";
~ */                        
