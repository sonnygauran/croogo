<?php

$counter = 0;
$success = false;
while(!$success) {
    $strCounter = str_pad($counter, 2, '0', STR_PAD_LEFT);

    $url = "http://alpha.meteomedia-portal.com/services/wetter4.php?api_key=portal-efd339395c80ad957acb695bb9758399&q=sve&leg=nil&a=image&x=554&y=554&srs=EPSG:900913&dt=20120816{$strCounter}0000&&x1=111.3134765625&x2=135.6591796875&y2=24.126701958681668&y1=0.5273363048115169";

    $img = '/home/luis/web/weather.com.ph/views/themed/weatherph/webroot/img/layers/try.png';
    $contents = file_get_contents($url);
    if (strlen($contents) == 239) {
        // BLACK IMAGE
        $counter += 3;
    } else {    
        $success = true;
    }
//    file
    file_put_contents($img, $contents);
    
}

?>
