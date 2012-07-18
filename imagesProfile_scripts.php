<?php
//var_dump($argv);
/*
if(!isset($argv[1])){
	echo "input startdate\n";
	exit;
}else if (!isset($argv[3])){
	$enddate = date('Y-m-d', strtotime('+3 days', strtotime($startdate)));
	echo "you did not input any date on the end date: result = startdate + 3days";
	$argv[3] = "+ 3days";
}
*/


$startscript = date('i:s');
echo "$startscript";
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
//startdate = "2012-07-15 12:00:00";
echo "\ninput enddate (format:Y-md H:i:s) +3 days if you did not input any values\n->";
$enddate = trim(fgets(STDIN));
/*
if (!isset($enddate)){
echo "[" . date('Y-m-d H:i:s', strtotime("+3 days", strtotime($startdate))) . "]\n ";
}
*/

function microtime_float() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float) $usec + (float) $sec);
}
$time_start = microtime_float();

$STATIC = array(
    'start' =>  date('YmdHis', strtotime($startdate)),
    'end' =>    date('YmdHis', strtotime($enddate)),
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
    'tempertature' =>   "http://alpha.meteomedia-portal.com/services/wetter4.php?dt=".$start."&api_key=portal-efd339395c80ad957acb695bb9758399&q=meh_ifm&leg=nil&a=image&x=554&y=554&srs=EPSG:900913&",
    'pressure' =>       "http://alpha.meteomedia-portal.com/services/wetter4.php?dt=".$start."&api_key=portal-efd339395c80ad957acb695bb9758399&q=meh_ifm&leg=nil&a=image&x=554&y=554&srs=EPSG:900913&p=QFF&",
    'satellite' =>      "http://alpha.meteomedia-portal.com/services/wetter4.php?dt=".$start."&api_key=portal-efd339395c80ad957acb695bb9758399&q=sve&leg=nil&a=image&x=554&y=554&srs=EPSG:900913&"
);

$coordinates = array(
    'all' => "&x1=111.32714843750325&x2=135.67285156249676&y2=24.41201768480203&y1=0.8402895756535625",
    'luzon' =>"&x1=116.1635742875163&x2=128.33642578124838&y2=22.582414770293372&y1=10.950736511266356",
    'visayas_mindanao' =>"&x1=119.12957421875164&x2=131.3024257812484&y2=15.627060843031257&y1=3.6491697643203027",
    'palawan_sulu' =>"&x1=114.53357421875162&x2=126.70642578124838&y2=14.56078202759215&y1=2.5464027989479354",
);

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
//echo $endscript;
$totalscript = date('i:s', strtotime($endscript) - strtotime($startscript));
echo "Profile: \n\n";
echo "Total file size: "."$file_mb MB \n";
echo "Total number of downloads: "."$counter\n";
//$totalscript2 = date('H:i:s', strtotime($totalscript));
//echo "$totalscript time";
$to_time = strtotime("$endscript");
$from_time = strtotime("$startscript");
echo "Total time accumulated: ";

$time_end = microtime_float();
$time = $time_end - $time_start;

echo "$time seconds\n";



/*while (strtotime($start) < strtotime($end)){
	$start = date('YmdHis',strtotime($res, strtotime($start)));
	
	//coordinates
	$COORDS = "&x1=111.32714843750325&x2=135.67285156249676&y2=24.41201768480203&y1=0.8402895756535625";
	$COORDS_LZN="&x1=116.1635742875163&x2=128.33642578124838&y2=22.582414770293372&y1=10.950736511266356";
	$COORDS_VZMD="&x1=119.12957421875164&x2=131.3024257812484&y2=15.627060843031257&y1=3.6491697643203027";
	$COORDS_PLSLU="&x1=114.53357421875162&x2=126.70642578124838&y2=14.56078202759215&y1=2.5464027989479354";

	exec("wget -O '$start temp_philippines.png' '$TEMP$COORDS'");
	
	exec("wget -O '$start temp_luzon.png' '$TEMP$COORDS_LZN'");
	exec("wget -O '$start temp_visayasMindano.png' '$TEMP$COORDS_VZMD'");
	exec("wget -O '$start temp_palawanSulu.png' '$TEMP$COORDS_PLSLU'");
	
	exec("wget -O '$start pressure_philippines.png' '$PRESSURE$COORDS'");
	exec("wget -O '$start pressure_luzon.png' '$PRESSURE$COORDS_LZN'");
	exec("wget -O '$start pressure_visayasMindanao.png' '$PRESSURE$COORDS_VZMD'");
	exec("wget -O '$start pressure_palawanSulu.png' '$PRESSURE$COORDS_PLSLU'");

	exec("wget -O '$start satellite_philippines.png' '$SATELLITE_VIS$COORDS'");
	
	$f_temp_philippines = "$start"." "."temp_philippines.png";
	$f_temp_luzon = "$start"." "."temp_luzon.png";
	$f_temp_visayasMindanao = "$start"." "."temp_visayasMindanao.png";
	$f_temp_palawanSulu = "$start"." "."temp_palawanSulu.png";
	
	$f_pressure_philippines = "$start"." "."pressure_philippines.png";	
	$f_pressure_luzon = "$start"." "."pressure_luzon.png";
	$f_pressure_visayasMindanao = "$start"." "."pressure_visayasMindanao.png";
	$f_pressure_palawanSulu = "$start"." "."pressure_palawanSulu.png";
        
        $satellite = "$start"." "."satellite_philippines.png";
        	
	//echo filesize($filename) . ' bytes';
	$f_temp_p = filesize($f_temp_philippines);
	$f_temp_l = filesize($f_temp_luzon);
        $f_temp_v = filesize($f_temp_visayasMindanao);
        $f_temp_s = filesize($f_temp_palawanSulu);

        $f_pressure_p = filesize($f_pressure_philippines);
	$f_pressure_l = filesize($f_pressure_luzon);
        $f_pressure_v = filesize($f_pressure_visayasMindanao);
//        $f_pressure_s = filesize($f_pressure_palawanSulu);

        $file_to_mb = round(($bytes / 1048576), 2);
        
     	$bytes = filesize($filename);
	$file_mb = round(($bytes / 1048576), 2);
	$filetotal = $filetotal + $file_mb;
	echo "$file_mb"." mb";
	$counter = $counter + 9;
}
	echo "\n\n number of files downloaded:".$counter;
	echo "\n file total ".$filetotal." mb";
**/	
//$enddate = date('Y-m-d|H:i:s', strtotime('+3 minutes', strtotime($startdate)));
//echo print_r($argv, true);
