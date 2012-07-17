<?php
App::import('Lib', 'Meteomedia.Abfrage');
App::import('Lib', 'Meteomedia.Curl');

App::import('Module', 'Meteomedia.Reading');


include 'weatherph_station_forecast.php';
/**
 * Acquires the measurements of Weather Stations
 */
class WeatherphStationReading extends WeatherphAppModel
{
    public $name = 'WeatherphStationReading';
    public $useTable = false;
    
    public function get($conditions = null, $fields = array(), $order = null, $recursive = null){
        
        include dirname(__FILE__) . '/auth.php';
        
        date_default_timezone_set('Asia/Manila');
        
        $daysStr = ($fields['conditions']['target_days'] > 1)? 'days' : 'day';
        $fields['conditions']['target_days'] = $fields['conditions']['target_days'];
        
        $stationId = $fields['conditions']['id'];
        $utch = $fields['conditions']['utch'];
 
        $startdatum = date('Ymd H:i:s', strtotime('-8 hours', strtotime(date('Ymd'))));    
        $enddatum = date('Ymd H:i:s', strtotime("+" . $fields['conditions']['target_days'] . $daysStr, strtotime($startdatum)));
        
        
        $startdatum = date('Ymd', strtotime($startdatum));    
        $enddatum = date('Ymd', strtotime($enddatum));
        
        $abfrageResults = array();
        
        $headersSpecimen = "Datum;utc;min;ort1;dir;ff;g3h;tl;rr;sy;rh;sy2;";
        
        $stationInfo = $this->getStationInfo($stationId, array("lat","lon"));
        
        //Grab stations readings  
        $url = "http://192.168.20.89/abfrage.php?stationidstring=$stationId&datumstart=$startdatum&datumend=$enddatum&utcstart=$startutc&utcend=$endutc&zeiten1=10m&tl=on&dir=on&ff=on&g3h=on&paramliste=rr,rh,sy,sy2&output=csv2&ortoutput=wmo6,name&aufruf=auto";
        
        $gum = $stationId.'_reading_'.sha1(end(explode('?',$url)));
        
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

        $resultsReadings = $this->csvToArray($curlResults, $headersSpecimen);
        
        $currentReadings = array();
        foreach($resultsReadings as $readings){
            if($readings['tl']!=''){
                
                //Determine sunrise and sunset for every location using latituted and longtitude
                $sunrise = date_sunrise(strtotime($readings['Datum']), SUNFUNCS_RET_STRING, $stationInfo['lat'], $stationInfo['lon'], 90);
                $sunset = date_sunset(strtotime($readings['Datum']), SUNFUNCS_RET_STRING, $stationInfo['lat'], $stationInfo['lon'], 90);

                $readings['sunrise'] = $sunrise;
                $readings['sunset'] = $sunset;
                
                $readings['ort1'] = explode('/', $readings['ort1']);
                unset($readings['ort1'][0]);
                $readings['ort1'] = implode('/', $readings['ort1']);
                
                //Determine weather symbol for certain utc time
                $readings['sy'] = $this->dayOrNightSymbol($readings['sy'], $readings['utc'], array("sunrise"=>$sunrise,"sunset"=>$sunset));
                
                // Replace the null values with hypen character and round it off to the nearest tenths
                $readings['tl'] = ($readings['tl'] == '')? '0' : round($readings['tl'],0);
                $readings['rr'] = ($readings['rr'] == '')? '0' : round($readings['rr'],0);
                $readings['rh'] = ($readings['rh'] == '')? '0' : round($readings['rh'],0);
                $readings['ff'] = ($readings['ff'] == '')? '0' : round($readings['ff'],0);
                $readings['g3h'] = ($readings['g3h'] == '')? '0' : round($readings['g3h'],0);
                
                // Translate raw data to wind direction image value
                $readings['dir'] = $this->showWindDirection($readings['dir']);
                
                $thierTime = strtotime($readings['Datum'].' '.$readings['utc'].':'.$readings['min']);
                $ourTime = strtotime('+8 hours', $thierTime);
                $readings['update'] = date('h:iA', $ourTime);
                //$readings['update'] = date('h:iA', $thierTime);
                
                $currentReadings[] = $readings;
                
            }
        }
        
        $currentReading = array_pop($currentReadings);
        $abfrageResults['reading'] = $currentReading;
        
        //$this->log(print_r($currentReadings, true));
        
        //Grab stations forecast  
        $url = "http://192.168.20.89/abfrage.php?stationidstring=$stationId&datumstart=$startdatum&datumend=$enddatum&utcstart=$startutc&utcend=$endutc&zeiten1=$utch&paramtyp=mos_mix_mm&mosmess=ja&tl=on&dir=on&ff=on&g3h=on&paramliste=rr,rh,sy,sy2&output=csv2&ortoutput=wmo6,name&aufruf=auto";

        $gum = $stationId.'_forecast_'.sha1(end(explode('?',$url)));        
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
        
        $resultsForecast = $this->csvToArray($curlResults, $headersSpecimen);
        
        $nowHour = date('H',strtotime($currentReading['update']));
        $nowHourRound = $nowHour - ($nowHour % 3);
        
        $hourStart = false;
        
        foreach($resultsForecast as $result){
            
            if(trim($result['tl'])!=''){
                
                //Determine sunrise and sunset for every location using latituted and longtitude
                $sunrise = date_sunrise(strtotime($result['Datum']), SUNFUNCS_RET_STRING, $stationInfo['lat'], $stationInfo['lon'], 90);
                $sunset = date_sunset(strtotime($result['Datum']), SUNFUNCS_RET_STRING, $stationInfo['lat'], $stationInfo['lon'], 90);

                $result['sunrise'] = $sunrise;
                $result['sunset'] = $sunset;

                //explode the ort1 raw data, grab only those needed
                $result['ort1'] = explode('/', $result['ort1']);
                unset($result['ort1'][0]);
                $result['ort1'] = implode('/', $result['ort1']);
                
                $abfrageResults['ort1'] = $result['ort1'];
                
                //Determine weather symbol for certain utc time
                $result['sy'] = $this->dayOrNightSymbol($result['sy'], $result['utc'], array("sunrise"=>$sunrise,"sunset"=>$sunset));
                
                // Replace the null values with hypen character and round it off to the nearest tenths
                $result['tl'] = ($result['tl'] == '')? '0' : round($result['tl'],0);
                $result['rr'] = ($result['rr'] == '')? '0' : round($result['rr'],0);
                $result['rh'] = ($result['rh'] == '')? '0' : round($result['rh'],0);
                $result['ff'] = ($result['ff'] == '')? '0' : round($result['ff'],0); 
                $result['g3h'] = ($result['g3h'] == '')? '0' : round($result['g3h'],0);
                
                // Translate raw date to 3 hourly range value
                //$result['utch'] = $result['utc'] . ':' . $result['min'] .' - '. sprintf('%02d',$result['utc'] + 3) .':'. $result['min'];
                $thierTime = strtotime($result['Datum'].' '.$result['utc'].':'.$result['min']);
                $ourTime = strtotime('+8 hours', $thierTime);
                $result['utch'] = date('H:iA', $ourTime);
                $result['ourtime'] = $nowHourRound;
                
                // Translate raw data to wind direction image value
                $result['dir'] = $this->showWindDirection($result['dir']);
                
                unset($result['ort1']);
                
                $readingTime = strtotime($currentReading['update']);
                
                if ($ourTime > $readingTime) {
                    $abfrageResults['forecast']['status'] = 'ok';
                    $abfrageResults['forecast'][] = $result;
                }else{
                    $abfrageResults['forecast']['status'] = 'none';
                }
            }
        }
        
        return $abfrageResults;
        
    }
    
    public function getDetailedReading($conditions = null, $fields = array(), $order = null, $recursive = null){
        
        include dirname(__FILE__) . '/auth.php';

        $stationId = $fields['conditions']['id'];
        $type = $fields['conditions']['type'];
        $timeRes = ($fields['conditions']['timeRes'] == NULL )? '1h' : $fields['conditions']['timeRes'];
        
        $startdatum = $fields['conditions']['startDatum'];
        // Input Validation
        $startdatum = ($startdatum == NULL)? date('Ymd') : date('Ymd',strtotime($startdatum));
        
        $enddatum = $fields['conditions']['endDatum'];
        
        // Input validation
        $enddatum = ($enddatum == NULL)? date('Ymd') : date('Ymd', strtotime($enddatum)); 
        
        
        //Grab stations readings  
        //$url = "http://192.168.20.89/abfrage.php?stationidstring=$stationId&datumstart=$startdatum&datumend=$enddatum&zeiten1=$timeRes&mosmess=ja&paramliste=tl,td,dir,ff,g1h,qff,rr,rh,sd1,gl1&output=csv2&ortoutput=wmo6,name&aufruf=auto";
        
        $url="http://192.168.20.89/abfrage.php?stationidstring=$stationId&datumstart=$startdatum&datumend=$enddatum&zeiten1=$timeRes&mosmess=ja&rain6=on&tn=on&paramliste=tl,tx,td,rh,ff,g1h,dir,qff,sh,rr,gl1h&output=csv2&ortoutput=wmo6,name&aufruf=auto";
        //$url="http://172.17.2.34/snowflake/api.php?query[measure]=dir,ff,g3h,tl,td,qff,rr,sh,gl1h,rh&station[id]=$stationId&time[from]=$startdatum&time[to]=$enddatum&zeiten1=$timeRes&time[suffix]=00:00&output[format]=CSV";

        
        
        $gum = $stationID . '_detailed_reading_' . sha1(end(explode('?', $url)));
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
        
        
        $headersSpecimen = 'Datum;utc;min;ort1;dir;ff;g1h;tl;td;tx;tn;qff;rr;sh;gl1h;rain6;rh;';
        //$headersSpecimen = 'Datum;utc;min;ort1;tl;td;rh;ff;g1h;dir;qff;sh;gl1;rr';
        
        $results = $this->csvToArray($curlResults, $headersSpecimen);
        
        foreach($results as $result){
            
            if(trim($result['tl'])!=''){
         
                //if(strtotime($result['Datum']) >= strtotime(date('Ymd'))){
                //explode the ort1 raw data, grab only those needed
                $result['ort1'] = explode('/', $result['ort1']);
                unset($result['ort1'][0]);
                $result['ort1'] = implode('/', $result['ort1']);
                
                
                $abfrageResults['ort1'] = $result['ort1']; 
//                $utcDate = strtotime('+8 hours', strtotime($result['Datum'] . $result['utc'] . ':' .$result['min']));
//                $result['Datum'] = date('Ymd', $utcDate);
//                $result['utc'] = date('H',$utcDate);
//                $result['min'] = date('m',$utcDate);
            
                $abfrageResults['readings'][$result['Datum']][] = $result;
                //}    
            }
       
        }
        
        $resultData = array();
        switch($type){
            case 'temp':
            case 'temperature':
                $resultData['sets'] = array(
                    'tl' => $this->popValArray($abfrageResults['readings'], 'tl'),
                    'td' => $this->popValArray($abfrageResults['readings'], 'td'),
                    'tx' => $this->amaxmin($abfrageResults['readings'], 'tx', 'max'),
                    'tn' => $this->amaxmin($abfrageResults['readings'], 'tn', 'min'),
                );
                    $resultData['settings'] = array(
                        'minor_interval' => 6,
                        'show_cross_label' => 'False',
                        'default_series_type' => 'Spline',
                        );
                    $resultData['series'] = array(
                        'tl' => array('name'=>'tlseries', 'style'=>'tlline', 'use_hand_cursor'=>'False', 'hoverable'=>'False'),
                        'td' => array('name'=>'tdseries', 'style'=>'tdline', 'use_hand_cursor'=>'False', 'hoverable'=>'False'),
                        'tx' => array('name'=>'txseries', 'style'=>'noline', 'use_hand_cursor'=>'False', 'hoverable'=>'False'),
                        'tn' => array('name'=>'tnseries', 'style'=>'noline', 'use_hand_cursor'=>'False', 'hoverable'=>'False'),
                    );
                    $resultData['additional'] = array(
                        'tl' => array('tooltip' => array('enabled' => 'false')),
                        'td' => array('tooltip' => array('enabled' => 'false')),
                        'tx' => array('marker' => array('enabled'=>'true', 'style'=>'dotred')),
                        'tn' => array('marker' => array('enabled'=>'true', 'style'=>'dotblue')),
                    );
                break;
            case 'wind':
                $resultData['sets'] = array(
                    'ff' => $this->popValArray($abfrageResults['readings'], 'ff'),
                    'g1h' => $this->popValArray($abfrageResults['readings'], 'g1h'), 
                );
                    $resultData['settings'] = array(
                        'minor_interval' => 3,
                        'show_cross_label' => 'True',
                        'default_series_type' => 'Spline',
                        );
                    $resultData['series'] = array(
                        'ff' => array('name'=>'ffseries', 'style'=>'ffline', 'use_hand_cursor'=>'False', 'hoverable'=>'False'),
                        'g1h' => array('name'=>'g1hseries', 'style'=>'g1hline', 'use_hand_cursor'=>'False', 'hoverable'=>'False'),
                    );
                    $resultData['additional'] = array(
                        'ff' => array('tooltip' => array('enabled' => 'false')),
                        'g1h' => array('tooltip' => array('enabled' => 'false')),
                    );
                break;
            case 'winddir':

                $windDir = $this->popValArray($abfrageResults['readings'], 'dir', NULL, '0.5');

                foreach($windDir as $dir){
                    $resultDir[] = array(
                        'x' => $dir['x'],
                        'y' => $dir['y'],
                        'marker' => $this->showWindDirection($dir['data']),
                    ); 
                }

                $resultData['sets'] = array(
                    'dir' => $resultDir,
                );
                    $resultData['settings'] = array(
                        'minor_interval' => 6,
                        'show_cross_label' => 'True',
                        'default_series_type' => 'Line',
                        );
                    $resultData['series'] = array(
                        'dir' => array('name'=>'dirline', 'style'=>'dirline', 'use_hand_cursor'=>'False', 'hoverable'=>'False'),
                        );
                break;
            case 'humidity':
                $resultData['sets'] = array(
                    'rh' => $this->popValArray($abfrageResults['readings'], 'rh'),
                );
                    $resultData['settings'] = array(
                        'minor_interval' => 3,
                        'show_cross_label' => 'False',
                        'default_series_type' => 'Spline',
                        );
                    $resultData['series'] = array(
                        'rh' => array('name'=>'rhseries', 'style'=>'rhline', 'use_hand_cursor'=>'False', 'hoverable'=>'False'),
                        );
                    $resultData['additional'] = array(
                        'rh' => array('tooltip' => array('enabled' => 'false')),
                    );
                break;
            case 'precip':
            case 'precipitation':
                $resultData['sets'] = array(
                    'rain6' => $this->popValArray($abfrageResults['readings'], 'rain6'),
                );
                    $resultData['settings'] = array(
                        'minor_interval' => 3,
                        'show_cross_label' => 'True',
                        'default_series_type' => 'Bar',
                        );
                    $resultData['series'] = array(
                        'rain6' => array('name'=>'rain6series', 'style'=>'', 'use_hand_cursor'=>'False', 'hoverable'=>'True'),
                        );
                    $resultData['additional'] = array(
                        'rain6' => array('tooltip' => array('enabled' => 'true')),
                    );
                break;
            case 'airpressure':
                $resultData['sets'] = array(
                    'qff' => $this->popValArray($abfrageResults['readings'], 'qff'),
                );
                    $resultData['settings'] = array(
                        'minor_interval' => 3,
                        'show_cross_label' => 'True',
                        'default_series_type' => 'Bar',
                        );
                    $resultData['series'] = array(
                        'qff' => array('name'=>'qffseries', 'style'=>'', 'use_hand_cursor'=>'False', 'hoverable'=>'True'),
                        );
                    $resultData['additional'] = array(
                        'qff' => array('tooltip' => array('enabled' => 'true')),
                    );
                break;
            case 'sunshine':
                $resultData['sets'] = array(
                    'sh' => $this->popValArray($abfrageResults['readings'], 'sh'),
                );
                    $resultData['settings'] = array(
                        'minor_interval' => 1,
                        'show_cross_label' => 'True',
                        'default_series_type' => 'Bar',
                        );
                    $resultData['series'] = array(
                        'sh' => array('name'=>'shseries', 'style'=>'sunshine', 'use_hand_cursor'=>'False', 'hoverable'=>'False'),
                        );
                    $resultData['additional'] = array(
                        'sh' => array('tooltip' => array('enabled' => 'true')),
                    );
                break;
            case 'globalradiation':
                $resultData['sets'] = array(
                    'gl1h' => $this->popValArray($abfrageResults['readings'], 'gl1h'),
                );
                    $resultData['settings'] = array(
                        'minor_interval' => 1,
                        'show_cross_label' => 'False',
                        'default_series_type' => 'Bar',
                        );
                    $resultData['series'] = array(
                        'gl1h' => array('name'=>'gl1hseries', 'style'=>'globalradiation', 'use_hand_cursor'=>'False', 'hoverable'=>'True'),
                        );
                    $resultData['additional'] = array(
                        'gl1h' => array('tooltip' => array('enabled' => 'true')),
                    );
                break;
        }
            
        $abfrageResults = ($type != NULL)?  $resultData : $abfrageResults;
        
        return $abfrageResults;
       
        
    }
    
    
    private function getAllReadings($stationId = arr){
        
        
        $Abfrage = new Abfrage($stationId);
        
        //Grab stations readings  
        $url = $Abfrage->generateURL($this->generateDate('reading', '10m'), array(
            'Temperature' => array(
                'low'
            ),
            'Wind' => array(
                'speed', 'direction'
            ),
            'Gust' => array(
                '3 hours'
            ),
            'Rainfall' => array(
                'Period'
            ),
            'Weather Symbols' => array(
                'Set 1', 'Set 2'
            ),
            'Humidity'
        ));
        
        // Get the string after the question-mark sign
        //$gum = $stationId.'_reading_'.sha1(end(explode('?',$url)));
        $curlResults = NULL;
        //if (!Cache::read($gum, '3hour')) {
            $curlResults = Curl::getData($url);
        //    Cache::write($gum, $curlResults, '3hour');
        //} else {
        //    $curlResults = Cache::read($gum, '3hour');
        //}
            
            $this->log($curlResults);
        
        
        
    } 
    
    public function fetch($conditions = null, $fields = array()){
        
        $reading_temp = new Reading();
        
        $station_id = $fields['conditions']['id'];
        $days_range = $fields['conditions']['days_range'];
        $time_frame = $fields['conditions']['time_frame'];
        
//        $this->log(print_r($fields['conditions'], TRUE));
        
        $return = array();
        
        $days_str = ($days_range > 1)? 'Days' : 'Day';
        
        $start_date = (trim($fields['conditions']['target_date']) == '')? date("Y-m-d") : date('Y-m-d', strtotime($fields['conditions']['target_date']));
        
        $end_date = ($days_range == NULL)? $start_date : date("Y-m-d", strtotime('-'.$days_range . $days_str, strtotime($start_date)));
        
//        $this->log('START DATE: '.$start_date);
//        $this->log('END DATE: '.$end_date);
        
        if($station_id != NULL){
            
            $station_info = $this->getStationInfo($station_id);
            
            if(count($station_info) > 0){
                
                // Get sunrise and sunset using current latituted and longtitude station
                $sunrise = $this->sunInfo($station_info['lat'], $station_info['lon'], 'sunrise');
                $sunset = $this->sunInfo($station_info['lat'], $station_info['lon'], 'sunset');

                $return['station_info'] = $station_info;

                $sql_conditions = array( 
                        'ort1 LIKE' => "%".$station_id ."%", 
                        'date(datum) BETWEEN ? AND ?' => array($end_date, $start_date),
                        'tl !=' => '',
                        );

                if($time_frame == '1h'){
                    $sql_conditions['min ='] = "00";
                }

                $station_readings = $reading_temp->find('all', array(
                    'conditions' => $sql_conditions,
                    'order' => "DATE(datum) , utc, min DESC",
                    ));

        //        $this->log(print_r($station_readings, TRUE));

                if(count($station_readings) > 0){

                    $readings_dummy = array();

                    foreach($station_readings as $readings){

                        $reading = $readings['Reading'];

                        $readings_dummy['date_time'] = date('Y-m-d H:i', strtotime($reading['datum'] . $reading['utc'] . ":" . $reading['min']));

                        $wind_direction = '-';
                        if(key_exists('dir', $reading)){
                            $wind_direction = $this->windDirection($reading['dir']);
                        }

                        $readings_dummy['temperature'] = '-';
                        if(key_exists('tl', $reading)){
                            $readings_dummy['temperature'] = (trim($reading['tl']) !='')? number_format($reading['tl'],0) . "&deg;C" : "-";
                        }

                        if(key_exists('td', $reading)){
                            $readings_dummy['dew_point'] = (trim($reading['td']) !='')? number_format($reading['td'],0) . "&deg;C" : "-";
                        }

                        if(key_exists('tn', $reading)){
                            $readings_dummy['temperature_min'] = (trim($reading['tn']) != '')? number_format($reading['tn'],0) : "-";
                        }

                        if(key_exists('tx', $reading)){
                            $readings_dummy['temperature_max'] = (trim($reading['tx']) !='')? number_format($reading['tx'],0) : "-";
                        }

                        if(key_exists('sy', $reading)){
                            $weather_condition = $this->dayOrNightSymbol($reading['sy'], $reading['utc'], array("sunrise"=>$sunrise,"sunset"=>$sunset));
                            $readings_dummy['weather_condition'] = (trim($weather_condition['description']) !='')? $weather_condition['description'] : "-";
                        }

                        $readings_dummy['rain1h'] = '-';
                        if(key_exists('rr1h', $reading)){
                            if(trim($reading['rr1h']) !=''){
                                $readings_dummy['rain1h'] = ((int)$reading['rr1h'] <= 0) ? round($reading['rr1h']) . "mm" : number_format($reading['rr1h'],1) . "mm";
                            }else{
                                $readings_dummy['rain1h'] = "-";
                            }
                            
                        }
                        
                        $readings_dummy['rain6h'] = '-';
                        if(key_exists('rain6', $reading)){
                            if(trim($reading['rain6']) !=''){
                                $readings_dummy['rain6h'] = ((int)$reading['rain6'] <= 0) ? round($reading['rain6']) . "mm" : number_format($reading['rain6'],1) . "mm";
                            }else{
                                $readings_dummy['rain6h'] = "-";
                            }
                            
                        }

                        if(key_exists('rh', $reading)){
                            $readings_dummy['humidity'] = (trim($reading['rh']) !='')? number_format($reading['rh'], 0) . "%" : "-";
                        }

                        if(key_exists('ff', $reading)){
                            $readings_dummy['wind_speed'] = (trim($reading['ff']) !='')? floor($reading['ff'] * 1.852 + 0.5) ."km/h" : "-";
                        }

                        $readings_dummy['wind_gust'] = '-';
                        if(key_exists('g1h', $reading)){
                            $readings_dummy['wind_gust'] = (trim($reading['g1h']) !='')? floor($reading['g1h'] * 1.852 + 0.5) . "km/h" : "-";
                        }

                        if(key_exists('dir', $reading)){
                            $readings_dummy['wind_direction'] = (trim($reading['dir']) !='')? $wind_direction['eng'] : "-";
                        }

                        if(key_exists('gl1h', $reading)){
                            $readings_dummy['global_radiation'] = (trim($reading['gl1h']) !='')? $reading['gl1h'] : "-";
                        }

                        $clean_readings[$reading['datum']][] = $readings_dummy;

                    }

                    krsort($clean_readings);

                    $return['readings'] = $clean_readings;

                }

                $this->log(print_r($return, TRUE));
                
            }
            
        }
        
        return $return;
        
    }
}

