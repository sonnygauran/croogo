<?php
//hello
$location = "../views/themed/weatherph/webroot/img/layers/layers_history/";

echo "input image resolution: (image resolution; 10(per 10 mins), 1(per hour),3(per 3 hours)\n->";
$resolution = trim(fgets(STDIN));
if ($resolution == 1){
    $res = "+1 hour";
}elseif ($resolution == 10){
    $res = "+10 minutes";
}else
    $res = "+3 hours";

echo "\ninput startdate (format:Y-m-d H:i:s)\n->";
$startdate = trim(fgets(STDIN));

echo "\ninput enddate (format:Y-md H:i:s) +3 days if you did not input any values\n->";
$enddate = trim(fgets(STDIN));

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
    'temperature' =>   "http://alpha.meteomedia-portal.com/services/wetter4.php?dt=".$start."&api_key=portal-efd339395c80ad957acb695bb9758399&q=meh_ifm&leg=nil&a=image&x=554&y=554&srs=EPSG:900913&",
    'pressure' =>       "http://alpha.meteomedia-portal.com/services/wetter4.php?dt=".$start."&api_key=portal-efd339395c80ad957acb695bb9758399&q=meh_ifm&leg=nil&a=image&x=554&y=554&srs=EPSG:900913&p=QFF&",
    'satellite' =>      "http://alpha.meteomedia-portal.com/services/wetter4.php?dt=".$start."&api_key=portal-efd339395c80ad957acb695bb9758399&q=sve&leg=nil&a=image&x=554&y=554&srs=EPSG:900913&"

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
            $filetotal += filesize("{$coordinate_key}_{$property_key}.png");
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


