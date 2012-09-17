<?php
date_default_timezone_set('UTC');
$script_location = dirname(__FILE__);
$location = "$script_location/../views/themed/weatherph/webroot/img/layers";
$date = date('YmdH', strtotime('+8 hours'));
$path = realpath($location);

//exec(rm -r '$path/$date'*.png);
exec("rm -r $path/$date*");
//rm -r /home/favestudent/web/weather.com.ph/views/themed/weatherph/webroot/img/layers/20120906*

echo $date;

?>
