<?php
date_default_timezone_set('UTC');
$script_location = dirname(__FILE__);
echo $script_location . "\n";
$location = "$script_location/../views/themed/weatherph/webroot/img/layers/";
$location2 = "$script_location/../views/themed/weatherph/webroot/img/layers";
if (!is_dir($location)) mkdir($location);

echo "\n\n";
echo exec("ls $location2");
exec("rm -r $location2/*.png");
echo "\n\n";
$res = "+1 hour +45 minutes";
$date = date('Y-m-d');
$startdate = $date;
$start = date('YmdHis', strtotime($startdate));
$enddate = date('YmdHis', strtotime("+2 days", strtotime($start)));

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
     'temperature' =>    "http://alpha.meteomedia-portal.com/services/wetter4.php?api_key=portal-efd339395c80ad957acb695bb9758399&q=meh_ifm&leg=nil&a=image&x=554&y=554&srs=EPSG:900913&",
     'pressure' =>       "http://alpha.meteomedia-portal.com/services/wetter4.php?api_key=portal-efd339395c80ad957acb695bb9758399&q=meh_ifm&leg=nil&a=image&x=554&y=554&srs=EPSG:900913&p=QFF&",

);

$coordinates = array(
    'all' => "&x1=111.3134765625&x2=135.6591796875&y2=24.126701958681668&y1=0.5273363048115169",
   // 'luzon' =>"&x1=119.21264648437499&x2=125.299072265625&y2=19.694314241825747&y1=13.870080100685891",
   // 'visayas_mindanao' =>"&x1=122.16796875&x2=128.25439453125&y2=12.651058133703483&y1=6.653695352486294",
   // 'palawan_sulu' =>"&x1=116.54296874999999&x2=122.62939453125001&y2=12.39365886237742&y1=6.391730485481462",
);

function microtime_float() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float) $usec + (float) $sec);
}
$time_start = microtime_float();
$success = false;

$original=0;
foreach($coordinates as $coordinate_key => $coordinate_value){
    foreach($properties as $property_key => $property_value){
			while (strtotime($start) < strtotime($end)){
				 $counter++;

           // $url = "{$property_value}{$coordinate_value}";
             $url = "{$property_value}dt={$start}&{$coordinate_value}";
             $start = date('YmdHis', strtotime($res, strtotime($start)));
//            echo "\n $url \n";
  //          exec("wget -O '$location2/$start{$coordinate_key}_{$property_key}.png' '$url'");
            $reg[] = "{$coordinate_key}_{$property_key}.png";
			 $img = "$location2/$start{$coordinate_key}_{$property_key}.png";
            $contents = file_get_contents($url);
 $strlen = strlen($contents);
                echo "\n------------------------------------------------\n";
                            echo $url;
                echo "\n------------------------------------------------\n";
            if ($strlen <= 239) {
                echo "\n---------------------EMPTY------------------------\n";
                }else {
                     file_put_contents($img, $contents);
                    $original++;
                    echo "\n SAVED \n";
                echo "\n------------------------------------------------\n";
            }

        }

        $start = $STATIC['start'];
		}
	}
echo "Profile: \n\n";
echo "Total number of downloads: "."$original out of:$counter\n";
echo "Total time accumulated: ";

$time_end = microtime_float();
$time = $time_end - $time_start;

echo "$time seconds\n";
$name = date ('Y-m-dH:i:s');
