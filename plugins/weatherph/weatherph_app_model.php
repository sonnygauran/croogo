<?php

App::import('Lib', 'Meteomedia.Xml');
App::import('Lib', 'Meteomedia.AnyChart');

class WeatherphAppModel extends AppModel {
        
    public function generateDate($type, $time_resolution = '1h', $hasUTC = true){

        // Adjust our time so that the data we get can match theirs
        $theirTime = strtotime(($type == 'charts' || $type == 'forecast') ? '-16 hours' : '-8 hours', strtotime(date('Ymd'))); 
        $format = array();
        
        $format['start_date'] = date('Ymd', $theirTime);
        $format['time_resolution'] = $time_resolution;
        
        if($hasUTC){
            $format['start_hour'] = date('H', $theirTime);
            $format['end_hour'] = '16';
        }
        switch($type){
            case 'reading':
                $format['end_date'] = date('Ymd', strtotime('+2 days', $theirTime));
                break;
            case 'forecast':
                $theirTime = strtotime(date('Ymd')); 
                $format['start_date'] = date('Ymd', $theirTime);    
                $format['start_hour'] = date('H', $theirTime);
                $format['end_date'] = date('Ymd', strtotime("+3 Days", $theirTime));;
                break;
            case 'chart':
                $format['end_date'] = date('Ymd', strtotime("+5 Days", $theirTime));;
                break;
        }

        return $format;
    }

    // Show what the moon phase for a certain date.
    protected function moon_phase($year, $month, $day) {

        $c = $e = $jd = $phase = $b = 0;

        if ($month < 3) {
            $year--;
            $month += 12;
        }
        ++$month;

        $c = 365.25 * $year;
        $e = 30.6 * $month;
        $jd = $c + $e + $day - 694039.09;   //jd is total days elapsed
        $jd /= 29.5305882;                  //divide by the moon cycle
        $b = (int) $jd;                     //int(jd) -> b, take integer part of jd
        $jd -= $b;                          //subtract integer part to leave fractional part of original jd
        $phase = round($jd * 8);            //scale fraction from 0-8 and round

        if ($phase >= 8)
            $phase = 0; //0 and 8 are the same so turn 8 into 0

        switch ($phase) {
            case 0:
                $moonphase = array("phase" => "New"); //New Moon
                break;
            case 1:
                $moonphase = array("phase" => "Waxing"); //Waxing Cresent Moon
                break;
            case 2:
                $moonphase = array("phase" => "Quarter"); //Queater Moon
                break;
            case 3:
                $moonphase = array("phase" => "Waxing"); //Waxing Gibbous Moon
                break;
            case 4:
                $moonphase = array("phase" => "Full"); //Waxing Gibbous Moon
                break;
            case 5:
                $moonphase = array("phase" => "Waning"); //Waning Gibbous Moon
                break;
            case 6:
                $moonphase = array("phase" => "Last"); //Last Quarter Moon
                break;
            case 7:
                $moonphase = array("phase" => "Waning"); //Waning Cresent Moon
                break;
            default:
                $moonphase = array("phase" => "Error");
                break;
        }

        $moonphase['phase_code'] = $phase;

        return $moonphase;
    }

    protected function dayOrNightSymbol($symbol = NULL, $utc = NULL, $meridiem = array()) {

        $symbol = number_format($symbol, 0);
        
        $weather_description = array(
            'Clear Sky',
            'Sunny',
            'Light Cloudy',
            'Partly Cloudy',
            'Cloudy',
            'Rain',
            'Rain and Snow, Sleet',
            'Snow',
            'Hail, Graupel',
            'Rain Shower',
            'Snow Shower',
            'Sleet Shower',
            'Fog',
            'Dense Fog',
            'Slipperiness',
            'Thunderstorms',
            'Drizzle',
            'Sandstorm'
        );
        
        if ($symbol == NULL || trim($symbol) == '') {

            return NULL;
            
        } else {
            
            $utc = (int) $utc + 3;

            $sunrise = date('H', strtotime($meridiem['sunrise']));
            $sunset = date('H', strtotime($meridiem['sunset']));

            if ($utc > $sunrise && $utc < $sunset) {
                $dayOrNight = 'day';
            } else {
                $dayOrNight = 'night';
            }
            
            $description = '';
            
            switch($dayOrNight){
                case 'day':
                    $description = $weather_description[$symbol];
                    break;
                case 'night':
                    $description = ( $symbol == 1 )? $weather_description[0] : $weather_description[$symbol]; 
                    break;
            }
            
            $weather_condition = array(
                'symbol' => $dayOrNight . '_' . $symbol,
                'description' => $description
            );

            return $weather_condition;
        }
    }

    protected function csvToArray($csv) {

        $rows = explode("\n", $csv);
        $headers = explode(';', $rows[0]);

        unset($rows[0]);

        $arrayResults = array();
        foreach ($rows as $key => $row) {
            if (trim($row) != '') {
                $params = explode(';', $row);
                foreach ($params as $key2 => $param) {
                    if ($headers[$key2] != '') {
                        $fieldName = $headers[$key2];
                        $uniqueKey = $key;
                        $arrayResults[$uniqueKey][$fieldName] = trim($param);
                    }
                }
            }
        }

        return $arrayResults;
    }

    public function getStationInfo($stationID = NULL, $keys = NULL) {
        if ($stationID == NULL) {
            $error = 'Station ID is required';
            $this->log($error);
            return NULL;
        } else {
            include dirname(__FILE__) . '/models/auth.php';
            $url = "http://192.168.20.89/abfrage.php?stationidstring=$stationID&ortsinfo=ja&paramtyp=mos_mix_mm&output=html&aufruf=auto";

            $gum = $stationID . '_info_' . sha1(end(explode('?', $url)));
            $curlResults = NULL;
            if (!Cache::read($gum, '3hour')) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_USERPWD, "{$karten['username']}:{$karten['password']}");
                curl_setopt($ch, CURLOPT_USERAGENT, "Weather.com.ph Curl Client 1.0");
                curl_setopt($ch, CURLOPT_TIMEOUT, 10); //times out after 10s 
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

                $curlResults = curl_exec($ch);
                curl_close($ch);
                Cache::write($gum, $curlResults, '3hour');
            } else {
                $curlResults = Cache::read($gum, '3hour');
            }

            $headersSpecimen = "id;name;typ;locator;wmo;reg;wmo1;wmo2;wmo3;icao;iat;aktiv;mos_gfs_kn;mos_ez_kn;mos_ez_mm;mos_preferred;lat;lon;alt;altp;xxx;spezial;org;land;bundesland;von;bis;wmoalt;wmoaltbis;metrilognummer;stationstyp;webname;webaktiv;dlat;dlon;ersatzstation;zeitzone;olsontimezone;webnosponsor;";

            $results = $this->csvToArray($curlResults, $headersSpecimen);

            if (count($results) > 1) {
                unset($results[2]);
            }
        }
        
        $results = $results[1];
        $return = array();
        
        if ($keys == NULL) {
            return $results;
        } else {
            if (is_array($keys)) {

                
                foreach ($keys as $key) {
                    $return[$key] = $results[$key];
                }
            } else {
                $return = $results[$keys];
            }
        }
        return $return;
    }

    protected function popValArray($array, $val, $xdata = NULL, $ydata = NULL) {
        foreach ($array as $key => $arr) {
            foreach ($arr as $ar) {
                $arrSet[] = array(
                'x' => (trim($xdata) != NULL) ? $xdata : strtotime($ar['Datum'] . ' ' . $ar['utc'] . ':' . $ar['min']),
                'y' => (trim($ydata) != NULL) ? $ydata : $ar[$val],
                'data' => $ar[$val],
                );
            }
        }
        return $arrSet;
    }
    
    protected function windDirection($wd = NULL){
        
         $lowest_value = 1000;
         
         $value = array();

            if ($wd == (-99) || $wd == (-999)) {
                $value = "-99";
            } else {

                if ($wd == 999) {
                    $value = 9;
                } else {

                    $wd_loc = $wd;

                    # 360
                    $diff = abs($wd_loc - 360);
                    if ($diff < $lowest_value) {
                        $lowest_value = $diff;
                        $value = array(
                            'numeric' => 7,
                            'eng' => 'North',
                            'symbol' => 'N'
                        );
                    }

                    # 45
                    $diff = abs($wd_loc - 45);
                    if ($diff < $lowest_value) {
                        $lowest_value = $diff;
                        $value = array(
                            'numeric' => 8,
                            'eng' => 'North East',
                            'symbol' => 'NE'
                        );
                    }

                    # 90 
                    $diff = abs($wd_loc - 90);
                    if ($diff < $lowest_value) {
                        $lowest_value = $diff;
                        $value = array(
                            'numeric' => 1,
                            'eng' => 'East',
                            'symbol' => 'E'
                        );
                    }

                    # 135
                    $diff = abs($wd_loc - 135);
                    if ($diff < $lowest_value) {
                        $lowest_value = $diff;
                        $value = array(
                            'numeric' => 2,
                            'eng' => 'South East',
                            'symbol' => 'SE'
                        );
                    }

                    # 180
                    $diff = abs($wd_loc - 180);
                    if ($diff < $lowest_value) {
                        $lowest_value = $diff;
                        $value = array(
                            'numeric' => 3,
                            'eng' => 'South',
                            'symbol' => 'S'
                        );
                    }

                    # 225
                    $diff = abs($wd_loc - 225);
                    if ($diff < $lowest_value) {
                        $lowest_value = $diff;
                        $value = array(
                            'numeric' => 4,
                            'eng' => 'South West',
                            'symbol' => 'SW'
                        );
                    }

                    # 270
                    $diff = abs($wd_loc - 270);
                    if ($diff < $lowest_value) {
                        $lowest_value = $diff;
                        $value = array(
                            'numeric' => 5,
                            'eng' => 'West',
                            'symbol' => 'W'
                        );
                    }

                    # 315
                    $diff = abs($wd_loc - 315);
                    if ($diff < $lowest_value) {
                        $lowest_value = $diff;
                        $value = array(
                            'numeric' => 6,
                            'eng' => 'North West',
                            'symbol' => 'NW'
                        );
                    }
                }
            }
            
        return $value;
        
    }

    protected function showWindDirection($wd = NULL) {

        if ($wd == NULL || trim($wd) == '') {

            return NULL;
        } else {

            $windDir = $this->windDirection($wd);
            return "wind_" . $windDir['numeric'];
        }
    }

    public function showWindDescription($windDirRaw = NULL, $windSpeed = NULL, $windGust = NULL) {

        //Creates an array for Beufort Scale(http://en.wikipedia.org/wiki/Beaufort_scale)
        $beaufort = array(
            "Calm",
            "Light Air",
            "Light Breeze",
            "Gentle Breeze",
            "Moderate Breeze",
            "Fresh Breeze",
            "Strong Breeze",
            "High wind, Moderate Gale, Near Gale",
            "Gale, Fresh Gale",
            "Strong Gale",
            "Storm",
            "Violent Storm",
            "Hurricane Force",
        );
        
        if($windDirRaw != NULL){
            $windDir = $this->windDirection($windDirRaw);
        }else{
            $windDir = '-';
        }
        
        
        if ($windSpeed < 1 and $windSpeed >= 0) {
            $windDirDesc = $beaufort[0];
        } elseif ($windSpeed <= 5.5 and $windSpeed >= 1.1){
            $windDirDesc = $beaufort[1];
        } elseif ($windSpeed <= 11 and $windSpeed >= 5.6){
            $windDirDesc = $beaufort[2];
        } elseif ($windSpeed <= 19 and $windSpeed >= 12){
            $windDirDesc = $beaufort[3];
        } elseif ($windSpeed <= 28 and $windSpeed >= 20){
            $windDirDesc = $beaufort[4];
        } elseif ($windSpeed <= 38 and $windSpeed >= 29){
            $windDirDesc = $beaufort[5];
        } elseif ($windSpeed <= 49 and $windSpeed >= 39){
            $windDirDesc = $beaufort[6];
        } elseif ($windSpeed <= 61 and $windSpeed >= 50){
            $windDirDesc = $beaufort[7];
        } elseif ($windSpeed <= 74 and $windSpeed >= 62){
            $windDirDesc = $beaufort[8];
        } elseif ($windSpeed <= 88 and $windSpeed >= 75){
            $windDirDesc = $beaufort[9];
        } elseif ($windSpeed <= 102 and $windSpeed >= 89){
            $windDirDesc = $beaufort[10];
        } elseif ($windSpeed <= 117 and $windSpeed >= 103){
            $windDirDesc = $beaufort[11];
        } elseif ($windSpeed >= 118){
            $windDirDesc = $beaufort[12];
        } else {
            $windDirDesc = '';
        }
        
        $windGustTxt = '';
        if((int)$windGust > 0) $windGustTxt = $windGust . 'km/h ';
         
        return $windDirDesc . ', <br />' . $windGustTxt .'from ' . $windDir['eng'];
        
        
    }
    
    public function arrayToAnyChartXML($conditions = null, $fields = array(), $order = null, $recursive = null) {

        $arrData = $fields['conditions']['arrData'];
        $type = strtolower($fields['conditions']['type']);

        
        $xml_string = '<?xml version="1.0" encoding="ISO-8859-1"?>';
        $xml_string = Xml::dumpsterDive(AnyChart::createChart($type, $arrData));

        $xml = simplexml_load_string($xml_string);

        return $xml;
    }

    // Used to get the maximum and minimum in a set of array.
    public function amaxmin($array, $val, $arg) {

        foreach ($array as $key => $arr) {

            foreach ($arr as $ar) {
                if (!isset($arrmaxmin[$key])) {
                    if (trim($ar[$val]) != '') {
                        $arrmaxmin[$key] = array(
                        'x' => strtotime($ar['Datum'] . ' ' . $ar['utc'] . ':' . $ar['min']),
                        'y' => $ar[$val],
                        'data' => $ar[$val],
                        );
                        break;
                    }
                }
            }


            foreach ($arr as $ar) {
                if (trim($ar[$val]) != '') {
                    if ($arg == 'max' && $ar[$val] >= $arrmaxmin[$key]['y']) {
                        $arrmaxmin[$key] = array(
                        'x' => strtotime($ar['Datum'] . ' ' . $ar['utc'] . ':' . $ar['min']),
                        'y' => trim($ar[$val]),
                        'data' => $ar[$val],
                        );
                    } elseif ($arg == 'min' && $ar[$val] <= $arrmaxmin[$key]['y']) {
                        $arrmaxmin[$key] = array(
                        'x' => strtotime($ar['Datum'] . ' ' . $ar['utc'] . ':' . $ar['min']),
                        'y' => trim($ar[$val]),
                        'data' => $ar[$val],
                        );
                    }
                }
            }
        }

        $arrData = array();
        foreach ($arrmaxmin as $maxmin) {
            $arrData[] = $maxmin;
        }

        return $arrData;
    }
    
    //Determine sunrise and sunset for every location using latituted and longtitude
    protected function sunInfo($lat = NULL, $lon = NULL, $type = NULL){
        
        $siteTimezone = Configure::read('Site.timezone');
        $Date = new DateTime(null, new DateTimeZone($siteTimezone));
        
        $sunInfo = date_sun_info(time(), $lat, $lon);
        
        if($type == 'sunrise'){
            return date("H:i:s", $sunInfo['sunrise'] + $Date->getOffset());
        }elseif($type == 'sunset'){
            return date("H:i:s", $sunInfo['sunset'] + $Date->getOffset());
        }else{
            return $sunInfo;
        }
        
    }

}