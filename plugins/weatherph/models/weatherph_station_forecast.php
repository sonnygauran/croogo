<?php

/**
 * Acquires the of Weather Forecast Stations
 */
class WeatherphStationForecast extends WeatherphAppModel
{
    
    public $name = 'WeatherphStationForecast';
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
        
        $startutc = date('H', strtotime($startdatum));
        $endutc = '00';
        
        $startdatum = date('Ymd', strtotime($startdatum));    
        $enddatum = date('Ymd', strtotime($enddatum));
        
        $abfrageResults = array();
        
        $headersSpecimen = "Datum;utc;min;ort1;dir;ff;g3h;tl;rr;sy;rh;sy2;";
        
        $stationInfo = $this->getStationInfo($stationId, array("lat","lon"));
        
        //Grab stations readings  
        $url = "http://192.168.20.89/abfrage.php?stationidstring=$stationId&datumstart=$startdatum&datumend=$enddatum&utcstart=$startutc&utcend=$endutc&zeiten1=10m&tl=on&dir=on&ff=on&g3h=on&paramliste=rr,rh,sy,sy2&output=csv2&ortoutput=wmo6,name&aufruf=auto";
        
        $this->log($url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, "{$karten['username']}:{$karten['password']}");
        curl_setopt($ch, CURLOPT_USERAGENT, "Weather.com.ph Curl Client 1.0");
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); //times out after 10s 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $curlResults = curl_exec($ch);
        curl_close($ch);
        
        $resultsReadings = $this->csvToArray($curlResults, $headersSpecimen);
        
        $currentReadings = array();
        foreach($resultsReadings as $readings){
            if($readings['tl']!=''){
                
                //Determine sunrise and sunset for every location using latituted and longtitude
                $readings['sunrise'] = date_sunrise(strtotime($readings['Datum']), SUNFUNCS_RET_STRING, $stationInfo['lat'], $stationInfo['lon'], 90);
                $readings['sunset'] = date_sunset(strtotime($readings['Datum']), SUNFUNCS_RET_STRING, $stationInfo['lat'], $stationInfo['lon'], 90);
                
                $readings['ort1'] = explode('/', $readings['ort1']);
                unset($readings['ort1'][0]);
                $readings['ort1'] = implode('/', $readings['ort1']);
                
                //Determine weather symbol for certain utc time
                $readings['sy'] = $this->dayOrNightSymbol(number_format($readings['sy'],0), $readings['utc'], array("sunrise"=>$readings['sunrise'],"sunset"=>$readings['sunset']));
                
                // Replace the null values with hypen character and round it off to the nearest tenths
                $readings['tl'] = ($readings['tl'] == '')? '0' : round($readings['tl'],0);
                $readings['rr'] = ($readings['rr'] == '')? '0' : round($readings['rr'],0);
                $readings['rh'] = ($readings['rh'] == '')? '0' : round($readings['rh'],0);
                $readings['ff'] = ($readings['ff'] == '')? '0' : round($readings['ff'],0);
                $readings['g3h'] = ($readings['g3h'] == '')? '0' : round($readings['g3h'],0);
                
                // Translate raw data to wind direction image value
                $readings['dir'] = $this->showWindDirection($readings['dir']);
                
                $utcDate = strtotime('+8 hours', strtotime($readings['Datum'] . $readings['utc'] . ':' .$readings['min']));
                $readings['Datum'] = date('Ymd', $utcDate);
                $readings['utc'] = date('H', $utcDate);
                $readings['min'] = date('m', $utcDate);
                
                $readings['update'] = date('h:iA', $utcDate);
                
                $currentReadings[] = $readings;
                
            }
        }
        
        $currentReading = array_pop($currentReadings);
        
        if(count($currentReading)>0){
            $abfrageResults['reading'] = $currentReading;
            $abfrageResults['reading']['status'] = 'ok';
        }else{
            $abfrageResults['reading']['status'] = 'none';
        }
        
        
        $this->log(print_r($currentReadings, true));
        
        //Grab stations forecast  
        $url = "http://192.168.20.89/abfrage.php?stationidstring=$stationId&datumstart=$startdatum&datumend=$enddatum&utcstart=$startutc&utcend=$endutc&zeiten1=$utch&paramtyp=mos_mix_mm&mosmess=ja&tl=on&dir=on&ff=on&g3h=on&paramliste=rr,rh,sy,sy2&output=csv2&ortoutput=wmo6,name&aufruf=auto";
        
        $this->log($url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, "{$karten['username']}:{$karten['password']}");
        curl_setopt($ch, CURLOPT_USERAGENT, "Weather.com.ph Curl Client 1.0");
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); //times out after 10s 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $curlResults = curl_exec($ch);
        curl_close($ch);
        
        $resultsForecast = $this->csvToArray($curlResults, $headersSpecimen);
        
        $nowHour = date('H',strtotime($currentReading['update']));
        $nowHourRound = $nowHour - ($nowHour % 3);
        
        $hourStart = false;
        
        $abfrageResults['forecast'] = array();
        foreach($resultsForecast as $result){
            
            if(trim($result['tl'])!=''){
                
                //Determine sunrise and sunset for every location using latituted and longtitude
                $result['sunrise'] = date_sunrise(strtotime($result['Datum']), SUNFUNCS_RET_STRING, $stationInfo['lat'], $stationInfo['lon'], 90);
                $result['sunset'] = date_sunset(strtotime($result['Datum']), SUNFUNCS_RET_STRING, $stationInfo['lat'], $stationInfo['lon'], 90);

                //explode the ort1 raw data, grab only those needed
                $result['ort1'] = explode('/', $result['ort1']);
                unset($result['ort1'][0]);
                $result['ort1'] = implode('/', $result['ort1']);
                
                $abfrageResults['ort1'] = $result['ort1'];
                
                //Determine weather symbol for certain utc time
                $result['sy'] = $this->dayOrNightSymbol(number_format($result['sy'],0), $result['utc'], array("sunrise"=>$result['sunrise'],"sunset"=>$result['sunset']));
                
                // Replace the null values with hypen character and round it off to the nearest tenths
                $result['tl'] = ($result['tl'] == '')? '0' : round($result['tl'],0);
                $result['rr'] = ($result['rr'] == '')? '0' : round($result['rr'],0);
                $result['rh'] = ($result['rh'] == '')? '0' : round($result['rh'],0);
                $result['ff'] = ($result['ff'] == '')? '0' : round($result['ff'],0); 
                $result['g3h'] = ($result['g3h'] == '')? '0' : round($result['g3h'],0);
                
                // Translate raw date to 3 hourly range value
                $thierTime = strtotime($result['Datum'].' '.$result['utc'].':'.$result['min']);
                $ourTime = strtotime('+8 hours', $thierTime);
                $result['utch'] = date('h:iA', $ourTime);
                $result['ourtime'] = $nowHourRound;
                
                // Translate raw data to wind direction image value
                $result['dir'] = $this->showWindDirection($result['dir']);
                
                unset($result['ort1']);
                
                $readingTime = (!isset($currentReading['update']))? date('Ymd H:i:s') : $currentReading['update'];
                
                $this->log(date('Ymd H:i:s', $ourTime). '-' . date('Ymd H:i:s', strtotime($readingTime)));
                
                if ($ourTime > strtotime($readingTime)) {
                    $abfrageResults['forecast']['status'] = 'ok';
                    $abfrageResults['forecast'][] = $result;
                }else{
                    $abfrageResults['forecast']['status'] = 'none';
                }
                
            }
        }
        
        //$this->log(print_r($abfrageResults, TRUE));
        
        return $abfrageResults;
        
    }
    
    public function getWeeklyForecast($conditions = null, $fields = array(), $order = null, $recursive = null){
        
        include dirname(__FILE__) . '/auth.php';
        
        date_default_timezone_set('Asia/Manila');
        
        $stationId = $fields['conditions']['id'];
        
        $startdatum = date('Ymd H:i:s', strtotime('-8 hours', strtotime(date('Ymd'))));    
        
        $startutc = date('H', strtotime($startdatum));
        $endutc = '00';
        
        $startdatum = date('Ymd', strtotime($startdatum));    
        $enddatum = date('Ymd', strtotime('+1 Day', strtotime(date('Ymd'))));
        
        $stationInfo = $this->getStationInfo($stationId, array("lat","lon"));
        
        //Grab stations readings  
        $url = "http://192.168.20.89/abfrage.php?stationidstring=$stationId&datumstart=$startdatum&datumend=$enddatum&utcstart=$startutc&utcend=$endutc&zeiten1=10m&tl=on&dir=on&ff=on&g3h=on&paramliste=rr,rh,sy,sy2&output=csv2&ortoutput=wmo6,name&aufruf=auto";
        
        $this->log($url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, "{$karten['username']}:{$karten['password']}");
        curl_setopt($ch, CURLOPT_USERAGENT, "Weather.com.ph Curl Client 1.0");
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); //times out after 10s 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $curlResults = curl_exec($ch);
        curl_close($ch);
        
        $headersSpecimen = "Datum;utc;min;ort1;dir;ff;g3h;tl;rr;sy;rh;sy2;";
        
        $resultsReadings = $this->csvToArray($curlResults, $headersSpecimen);
        
        $currentReadings = array();
        foreach($resultsReadings as $readings){
            if($readings['tl']!=''){
                
                //Determine sunrise and sunset for every location using latituted and longtitude
                $readings['sunrise'] = date_sunrise(strtotime($readings['Datum']), SUNFUNCS_RET_STRING, $stationInfo['lat'], $stationInfo['lon'], 90);
                $readings['sunset'] = date_sunset(strtotime($readings['Datum']), SUNFUNCS_RET_STRING, $stationInfo['lat'], $stationInfo['lon'], 90);
                
                $readings['ort1'] = explode('/', $readings['ort1']);
                unset($readings['ort1'][0]);
                $readings['ort1'] = implode('/', $readings['ort1']);
                
                //Determine weather symbol for certain utc time
                $readings['sy'] = $this->dayOrNightSymbol(number_format($readings['sy'],0), $readings['utc'], array("sunrise"=>$readings['sunrise'],"sunset"=>$readings['sunset']));
                
                // Replace the null values with hypen character and round it off to the nearest tenths
                $readings['tl'] = ($readings['tl'] == '')? '0' : round($readings['tl'],0);
                $readings['rr'] = ($readings['rr'] == '')? '0' : round($readings['rr'],0);
                $readings['rh'] = ($readings['rh'] == '')? '0' : round($readings['rh'],0);
                $readings['ff'] = ($readings['ff'] == '')? '0' : round($readings['ff'],0);
                $readings['g3h'] = ($readings['g3h'] == '')? '0' : round($readings['g3h'],0);
                
                $readings['moonphase'] = $this->moon_phase(date('Y', strtotime($readings['Datum'])), date('m', strtotime($readings['Datum'])), date('d', strtotime($readings['Datum'])));
                
                // Translate raw data to wind direction image value
                $readings['dir'] = $this->showWindDirection($readings['dir']);
                
                $utcDate = strtotime('+8 hours', strtotime($readings['Datum'] . $readings['utc'] . ':' .$readings['min']));
                $readings['Datum'] = date('Ymd', $utcDate);
                $readings['utc'] = date('H', $utcDate);
                $readings['min'] = date('m', $utcDate);
                
                $readings['update'] = date('h:iA', $utcDate);
                
                $currentReadings[] = $readings;
                
            }
        }
        
        $currentReading = array_pop($currentReadings);
        
        if(count($currentReading)>0){
            $abfrageResults['reading'] = $currentReading;
            $abfrageResults['reading']['status'] = 'ok';
        }else{
            $abfrageResults['reading']['status'] = 'none';
        }
        
        $enddatum = date('Ymd', strtotime("+5 Days", strtotime($startdatum)));
        
        //Grab stations forecast  
        $url = "http://192.168.20.89/abfrage.php?stationidstring=$stationId&datumstart=$startdatum&datumend=$enddatum&&zeiten1=3h&paramtyp=mos_mix_mm&mosmess=ja&tl=on&dir=on&ff=on&g3h=on&paramliste=rr,rh,sy,sy2&output=csv2&ortoutput=wmo6,name&timefill=nein&verknuepft=nein&aufruf=auto";
        
        $this->log($url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, "{$karten['username']}:{$karten['password']}");
        curl_setopt($ch, CURLOPT_USERAGENT, "Weather.com.ph Curl Client 1.0");
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); //times out after 10s ,
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $curlResults = curl_exec($ch);
        curl_close($ch);
        
        $headersSpecimen = "Datum;utc;min;ort1;dir;ff;g3h;tl;rr;sy;rh;sy2;";
        
        $results = $this->csvToArray($curlResults, $headersSpecimen);
        
        foreach($results as $result){
            
            if(trim($result['tl'])!=''){
                
                //Determine sunrise and sunset for every location using latituted and longtitude
                $result['sunrise'] = date_sunrise(strtotime($result['Datum']), SUNFUNCS_RET_STRING, $stationInfo['lat'], $stationInfo['lon'], 90);
                $result['sunset'] = date_sunset(strtotime($result['Datum']), SUNFUNCS_RET_STRING, $stationInfo['lat'], $stationInfo['lon'], 90);
                
                //Determine weather symbol for certain utc time
                $result['sy'] = $this->dayOrNightSymbol(number_format($result['sy'],0), $result['utc'], array("sunrise"=>$result['sunrise'],"sunset"=>$result['sunset']));
                
                // Replace the null values with hypen character and round it off to the nearest tenths
                $result['tl'] = ($result['tl'] == '')? '0' : round($result['tl'],0);
                $result['rr'] = ($result['rr'] == '')? '0' : round($result['rr'],0);
                $result['rh'] = ($result['rh'] == '')? '0' : round($result['rh'],0);
                $result['ff'] = ($result['ff'] == '')? '0' : round($result['ff'],0);
                $result['g3h'] = ($result['g3h'] == '')? '0' : round($result['g3h'],0);
                
                // Translate raw date to 3 hourly range value
                $thierTime = strtotime($result['Datum'].' '.$result['utc'].':'.$result['min']);
                $ourTime = strtotime('+8 hours', $thierTime);
                $result['utch'] = date('H:iA', $ourTime);
                
                // Translate raw data to wind direction image value
                $result['dir'] = $this->showWindDirection($result['dir']);
                
                unset($result['ort1']);
                
                $abfrageResults['forecast'][$result['Datum']][] = $result;
                
            }
        }
        
        $abfrageResults['stationId'] = $stationId;
        $readings['ort1'] = explode('/', $readings['ort1']);
        $readings['ort1'] = $readings['ort1'][1];
        $abfrageResults['stationName'] = $readings['ort1'];
       
        return $abfrageResults;
        
    }
    
    public function getDetailedForecast($conditions = null, $fields = array(), $order = null, $recursive = null){
        
        include dirname(__FILE__) . '/auth.php';
        
        $stationId = $fields['conditions']['id'];
        $type = $fields['conditions']['type'];
        $timeRes = ($fields['conditions']['timeRes'] == NULL )? '1h' : $fields['conditions']['timeRes'];
        
        $startdatum = $fields['conditions']['startDatum'];
        
        $startdatum = date('Ymd H:i:s', strtotime('-8 hours', strtotime(date('Ymd'))));    
        $enddatum = date('Ymd H:i:s', strtotime("+5 Days ", strtotime(date('Ymd'))));
        
        $startutc = date('H', strtotime($startdatum));
        $endutc = '00';
        
        $startdatum = date('Ymd', strtotime($startdatum));    
        $enddatum = date('Ymd', strtotime($enddatum));
        
        $unit = '';
        switch($type){
            case 'wind':
                $unit = 2;
                break;
        }
        
        //Grab stations readings  
        $url = "http://192.168.20.89/abfrage.php?stationidstring=$stationId&datumstart=$startdatum&datumend=$enddatum&utcstart=$startutc&utcend=$endutc&zeiten1=$timeRes&paramtyp=mos_mix_mm&unit=$unit&mosmess=ja&rain6=on&paramliste=tl,tx,tn,td,rh,ff,g1h,dir,qff,sh,gl1h&output=csv2&ortoutput=wmo6,name&timefill=nein&verknuepft=nein&aufruf=auto";
        
        $this->log($url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, "{$karten['username']}:{$karten['password']}");
        curl_setopt($ch, CURLOPT_USERAGENT, "Weather.com.ph Curl Client 1.0");
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); //times out after 10s ,
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $curlResults = curl_exec($ch);
        curl_close($ch);
        
        $headersSpecimen = 'Datum;utc;min;ort1;dir;ff;g1h;tl;td;tx;tn;qff;sh;gl1h;rain6;rh;';
        
        $results = $this->csvToArray($curlResults, $headersSpecimen);
        
        foreach($results as $result){
            
            if(trim($result['tl'])!=''){
         
                if(strtotime($result['Datum']) >= strtotime(date('Ymd'))){
                //explode the ort1 raw data, grab only those needed
                $result['ort1'] = explode('/', $result['ort1']);
                unset($result['ort1'][0]);
                $result['ort1'] = implode('/', $result['ort1']);
                
                $abfrageResults['ort1'] = $result['ort1']; 
                
                $utcDate = strtotime('+8 hours', strtotime($result['Datum'] . $result['utc'] . ':' .$result['min']));
                $result['Datum'] = date('Ymd', $utcDate);
                $result['utc'] = date('H', $utcDate);
                $result['min'] = date('m', $utcDate);
                
                $abfrageResults['forecast'][$result['Datum']][] = $result;
                }    
            }
       
        }
        
        
        
        $resultData = array();
        switch($type){
            case 'temp':
            case 'temperature':
                
                $resultData['sets'] = array(
                    'tl' => $this->popValArray($abfrageResults['forecast'], 'tl'),
                    'td' => $this->popValArray($abfrageResults['forecast'], 'td'),
                    'tx' => $this->highTemp($this->popValArray($abfrageResults['forecast'], 'tx')),
                    'tn' => $this->LowTemp($this->popValArray($abfrageResults['forecast'], 'tn')),
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
                    'ff' => $this->popValArray($abfrageResults['forecast'], 'ff'),
                    'g1h' => $this->popValArray($abfrageResults['forecast'], 'g1h'), 
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

                $windDir = $this->popValArray($abfrageResults['forecast'], 'dir', NULL, '0.5');

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
                    'rh' => $this->popValArray($abfrageResults['forecast'], 'rh'),
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
                    'rain6' => $this->popValArray($abfrageResults['forecast'], 'rain6'),
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
                    'qff' => $this->popValArray($abfrageResults['forecast'], 'qff'),
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
                    'sh' => $this->popValArray($abfrageResults['forecast'], 'sh'),
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
                    'gl1h' => $this->popValArray($abfrageResults['forecast'], 'gl1h'),
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
    
    public function arrayToAnyChartXML ($conditions = null, $fields = array(), $order = null, $recursive = null){
        
        $arrData = $fields['conditions']['arrData'];
        $type = strtolower($fields['conditions']['type']);
        
        $xml_string = 
            '<?xml version="1.0" encoding="ISO-8859-1"?>
                <anychart>
                <margin all="3" bottom="0" left="10" right="8"/>
                <settings>
                    <locale>
                        <date_time_format>
                            <format>%u</format>';
        
        if($type == 'precipitation' || $type == 'precip'){
        $xml_string .= '<months>
                            <names>
                                January,February,March,April,May,June,July,August,September,October,November,December
                            </names>
                            <short_names>Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sep,Oct,Nov,Dec</short_names>
                        </months>';
        }
        
        $xml_string .= '<week_days start_from="Sunday">
                                <short_names>
                                    <![CDATA[ Su.,Mo.,Tu.,We.,Th.,Fr.,Sa. ]]>
                                </short_names>
                            </week_days>
                        </date_time_format>
                    </locale>
                </settings>
                <charts>
                    <chart plot_type="Scatter">
                        <chart_settings>
                            <title enabled="false"/>
                            <axes>
                                <x_axis enable="true">
                                    <scale type="DateTime" minimum_offset="0" maximum_offset="0" minor_interval="'.$arrData['settings']['minor_interval'].'" minor_interval_unit="Hour" major_interval="1" major_interval_unit="Day"/>
                                    <title enabled="false"/>
                                    <labels enabled="True" show_cross_label="'.$arrData['settings']['show_cross_label'].'" allow_overlap="true">
                                        <format>
                                            <![CDATA[ ]]>
                                            {%Value}{dateTimeFormat:%ddd %dd.%MM.}
                                        </format>
                                        <font family="Arial" color="#444444" size="11"/>
                                    </labels>
                                    <major_grid enabled="True" interlaced="True">
                                        <interlaced_fills>
                                            <even>
                                                <fill color="rgb(245,245,245)" opacity="1"/>
                                            </even>
                                        </interlaced_fills>
                                    </major_grid>
                                </x_axis>';
        
        
        if($type == 'winddir'){
        $xml_string .= '        <y_axis enabled="false">
                                    <scale type="Linear" maximum="1" minimum="0"/>
                                    <title enabled="false"/>
                                    <labels enabled="false">
                                        <format>{%Value}{numDecimals:0}</format>
                                        <font family="Arial" color="#444444" size="11"/>
                                    </labels>
                                </y_axis>';
        }else if($type=='humidity'){
        $xml_string .=  '       <y_axis>
                                    <scale type="Linear" maximum="100" minimum="0" maximum_offset="0.01" minimum_offset="0.01" />
                                    <title enabled="false"/>
                                    <labels>
                                        <format>{%Value}{numDecimals:0}</format>
                                        <font family="Arial" color="#444444" size="11"/>
                                    </labels>
                                </y_axis>';
        }else{
        $xml_string .=  '       <y_axis>
                                    <!--scale type="Linear" maximum="auto" minimum="auto" maximum_offset="0.01" minimum_offset="0.01"/-->
                                    <title enabled="false"/>
                                    <labels>
                                        <format>{%Value}{numDecimals:0}</format>
                                        <font family="Arial" color="#444444" size="11"/>
                                    </labels>
                                </y_axis>';
        }
        
        $xml_string .= '
                                <extra>
                                    <y_axis name="y2" enabled="False">
                                        <title enabled="false"/>
                                        <labels>
                                            <format/>
                                        </labels>
                                    </y_axis>
                                </extra>
                            </axes>
                        <chart_background enabled="false">
                            <effects enabled="false"/>
                            <border enabled="false"/>
                            <inside_margin all="0"/>
                        </chart_background>
                        <data_plot_background enabled="false">
                            <effects enabled="false"/>
                        </data_plot_background>
                    </chart_settings>';
        
        $xml_string .= '
                <data_plot_settings default_series_type="'.$arrData['settings']['default_series_type'].'">
                    ';
        
        // Settings
        // Temperature
        if($type == 'temp' || $type == 'temperature'){
        $xml_string .= '<line_series>
                            <marker_settings enabled="false"/>
                            <line_style>
                                <line enabled="true" thickness="2" caps="round" joints="round"/>
                            </line_style>
                            <tooltip_settings enabled="true">
                                <format>
                                    <![CDATA[ {%YValue}{numDecimals:1} ]]>
                                </format>
                            </tooltip_settings>
                        </line_series>';
        }
        
        // Precipitation (rain6)
        if($type == 'precipitation' || $type == 'precip'){
        $xml_string .= '<bar_series point_padding="0" scatter_point_width="4.7%">
                            <bar_style>
                                <fill enabled="true" type="Gradient">
                                    <gradient type="Radial">
                                        <key position="0" color="#0036d9"/>
                                        <!-- innen -->
                                        <key position="1" color="#002080"/>
                                    </gradient>
                                </fill>
                                <border enabled="False"/>
                                <effects enabled="False"/>
                            </bar_style>
                        </bar_series>';    
            
        }
        
        // Air Pressure (qff)
        if($type == 'airpressure'){
        $xml_string .= '<bar_series point_padding="0" scatter_point_width="4.7%">
                            <bar_style>
                                <fill enabled="true" type="Gradient">
                                    <gradient type="Radial">
                                        <key position="0" color="#F5E616"/>
                                        <!-- innen -->
                                        <key position="1" color="#E3D50B"/>
                                    </gradient>
                                </fill>
                                <border enabled="False"/>
                                <effects enabled="False"/>
                            </bar_style>
                        </bar_series>';    
            
        }
        
        // Humidity, Wind and Wind Direction
        if($type == 'humidity' || $type == 'wind'){
        $xml_string .= '<line_series>
                            <marker_settings enabled="false"/>
                            <line_style>
                                <line enabled="true" thickness="2" caps="round" joints="round"/>
                            </line_style>
                        </line_series>';
        }elseif($type == 'winddir'){
        $xml_string .= '<line_series>
                            <marker_settings enabled="true"/>
                            <line_style>
                                <line enabled="true" thickness="2" caps="round" joints="round"/>
                            </line_style>
                        </line_series>';
        }
        
        // Sunshine
        if($type == 'sunshine'){
        $xml_string .= '<bar_series scatter_point_width="0.4%" group_padding="0" point_padding="0">
                            <bar_style>
                                <fill enabled="true" type="Solid" color="#fff000" thickness="1"></fill>
                                <border enabled="True" type="Gradient">
                                    <gradient angle="90">
                                        <key position="0" color="#ffd500"/>
                                        <key position="0.3" color="#fff000"/>
                                        <key position="1" color="#fff000"/>
                                    </gradient>
                                </border>
                                <effects enabled="False"/>
                            </bar_style>
                        </bar_series>';
        }
        
        if($type == 'globalradiation'){
        $xml_string .= '<bar_series scatter_point_width="0.4%" group_padding="0" point_padding="0">
                            <bar_style>
                                <fill enabled="true" type="Solid" color="#182DCC" thickness="1"></fill>
                                <effects enabled="False"/>
                            </bar_style>
                        </bar_series>';   
            
        }
        
        $xml_string .= '
                </data_plot_settings>
                <styles>';
        
        // Styles
        if($type == 'temperature' || $type == 'temp'){
        $xml_string .= '
                    <line_style name="tlline" color="#c80000">
                        <line thickness="2"/>
                    </line_style>
                    <line_style name="tdline" color="#00c800">
                        <line thickness="2"/>
                    </line_style>
                    <line_style name="noline">
                        <line enabled="False"/>
                    </line_style>
                    <marker_style name="dotblue" color="blue">
                        <marker size="3" type="circle"/>
                    </marker_style>
                    <marker_style name="dotred" color="#c80000">
                        <marker size="3" type="circle"/>
                    </marker_style>';
        }elseif($type == 'wind'){
            
        $xml_string .= '
                    <line_style name="ffline" color="#966400"/>
                    <line_style name="g1hline" color="#c800aa"/>';    
            
        }elseif($type == 'winddir'){
            
        $xml_string .= '
                    <line_style name="dirline" color="green">
                        <line enabled="false"/>
                    </line_style>
                    <marker_style name="wind_1"><!-- EAST -->
                        <marker type="Image" image_url="../theme/weatherph/img/w1.png" size="23"/>
                    </marker_style>
                    <marker_style name="wind_2"><!-- SOUTH EAST -->
                        <marker type="Image" image_url="../theme/weatherph/img/w2.png" size="23"/>
                    </marker_style>
                    <marker_style name="wind_3"><!-- SOUTH -->
                        <marker type="Image" image_url="../theme/weatherph/img/w3.png" size="23"/>
                    </marker_style>
                    <marker_style name="wind_4"><!-- SOUTH WEST -->
                        <marker type="Image" image_url="../theme/weatherph/img/w4.png" size="23"/>
                    </marker_style>
                    <marker_style name="wind_5"><!-- WEST -->
                        <marker type="Image" image_url="../theme/weatherph/img/w5.png" size="23"/>
                    </marker_style>
                    <marker_style name="wind_6"><!-- NORTH WEST -->
                        <marker type="Image" image_url="../theme/weatherph/img/w6.png" size="23"/>
                    </marker_style>
                    <marker_style name="wind_7"><!-- NORTH -->
                        <marker type="Image" image_url="../theme/weatherph/img/w7.png" size="23"/>
                    </marker_style>
                    <marker_style name="wind_8"><!-- NORTH EAST -->
                        <marker type="Image" image_url="../theme/weatherph/img/w8.png" size="23"/>
                    </marker_style>
                    <marker_style name="wind_9"><!-- NO WIND DIRECTION -->
                        <marker type="Image" image_url="../theme/weatherph/img/w9.png" size="23"/>
                    </marker_style>
                    ';
            
        }elseif($type == 'humidity'){
            
        $xml_string .= '<line_style name="rhline" color="#00c800"/>';
            
        }
        
        $xml_string .= '
                </styles>
                <data>';
        
        //$this->log($arrData);
            
        foreach($arrData['sets'] as $key=>$sets){

        $xml_string .= '<series';

            foreach($arrData['series'][$key] as $index=>$attr){
                $xml_string .= (trim($attr) != '')? ' '. $index . '="' . $attr .'"' : '';
            }

            $xml_string .= '>';

            if(isset($arrData['additional'][$key])){

                foreach($arrData['additional'][$key] as $index=>$addtnl){

                    $xml_string .= '<'.$index.' ';

                    foreach($addtnl as $key2=>$add){
                        $xml_string .= ' ' . $key2 . '="'.$add.'"';
                    }
                    $xml_string .= '/>';
                }

            }

            foreach($sets as $set){
                $xml_string .= '<point x="'.$set['x'].'" y="'.$set['y'].'">';
                $xml_string .= (isset($set['marker']))? '<marker style="'.$set['marker'].'" />' : '';
                $xml_string .= '<!-- '.date('Y-m-d H:i:s', $set['x']).'-->';
                $xml_string .= '</point>';
            }

        $xml_string .='</series>';


        }
         
        $xml_string .= '
                </data>
                </chart>
                </charts>
            </anychart>';
        
        //$this->log($xml_string);
        
        $xml = simplexml_load_string($xml_string);

        return $xml;
        
    }
    
    public function csvToArray($csv, $headersSpecimen){
        
        //Convert 
        $expected = strstr($csv, $headersSpecimen);
       
        if ($expected == '') {
          $error = 'There was an error generating the CSV from '.$url;
           $this->log($error);
           throw new Exception('There was an error generating the CSV');
           return array();
        }

        $rows = explode("\n", $csv);
        $headers = explode(';', $rows[0]);
        
        unset($rows[0]);

        $arrayResults = array();
        foreach($rows as $key=>$row){
            if(trim($row) != ''){  
            $params = explode(';' , $row);
            foreach($params as $key2=>$param){
                    if($headers[$key2]!=''){
                        $arrayResults[$key]
                            [$headers[$key2]] = trim($param);
                    }
                } 
            }

        }
            
        return $arrayResults;
        
    }
    
    public function getStationInfo($stationID = NULL, $keys = NULL){
        
        if($stationID == NULL){
            
            $error = 'Station ID is required'.$url;
            $this->log($error);
            throw new Exception('Station ID is required');
            return NULL;
            
        }else{
             
            include dirname(__FILE__) . '/auth.php';

            $url = "http://192.168.20.89/abfrage.php?stationidstring=$stationID&ortsinfo=ja&paramtyp=mos_mix_mm&output=html&aufruf=auto";

            //$this->log($url);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERPWD, "{$karten['username']}:{$karten['password']}");
            curl_setopt($ch, CURLOPT_USERAGENT, "Weather.com.ph Curl Client 1.0");
            curl_setopt($ch, CURLOPT_TIMEOUT, 10); //times out after 10s 
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

            $curlResults = curl_exec($ch);
            curl_close($ch);
            
            $headersSpecimen = "id;name;typ;locator;wmo;reg;wmo1;wmo2;wmo3;icao;iat;aktiv;mos_gfs_kn;mos_ez_kn;mos_ez_mm;mos_preferred;lat;lon;alt;altp;xxx;spezial;org;land;bundesland;von;bis;wmoalt;wmoaltbis;metrilognummer;stationstyp;webname;webaktiv;dlat;dlon;ersatzstation;zeitzone;olsontimezone;webnosponsor;";
        
            $results = $this->csvToArray($curlResults, $headersSpecimen);
            
            if(count($results) > 1) unset($results[2]);
            
        }
        
        $results = $results[1];
        
        if($keys == NULL){
            return $results;
        }else{
            if(is_array($keys)){
                
                $new_results = array();
                
                foreach($keys as $key){
                    $new_results[$key] = $results[$key];
                }
                
                return $new_results;
                
            }else{
                return $results[$keys];
            }
            
        }
        
    }
    
    private function dayOrNightSymbol($symbol = NULL, $utc = NULL, $meridiem = array()){
        
        if($symbol == NULL || trim($symbol) == ''){
   
              return NULL;
              
        }else{
            
            $utc = (int)$utc + 3;
            
            $sunrise = date('H', strtotime($meridiem['sunrise']));
            $sunset = date('H', strtotime($meridiem['sunset']));
            
            if($utc > $sunrise && $utc < $sunset){
                $dayOrNight = 'day';
            }else{
                $dayOrNight = 'night';
            }
            
            return $dayOrNight . '_' . $symbol;
            
        }
        
    }
    
    private function showWindDirection($wd = NULL) {
        
        if($wd == NULL || trim($wd) == ''){
            
            return NULL;
            
        }else{

            $lowest_value = 1000;

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
                        $value = 1;
                    }

                    # 45
                    $diff = abs($wd_loc - 45);
                    if ($diff < $lowest_value) {
                        $lowest_value = $diff;
                        $value = 8;
                    }

                    # 90
                    $diff = abs($wd_loc - 90);
                    if ($diff < $lowest_value) {
                        $lowest_value = $diff;
                        $value = 7;
                    }

                    # 135
                    $diff = abs($wd_loc - 135);
                    if ($diff < $lowest_value) {
                        $lowest_value = $diff;
                        $value = 6;
                    }

                    # 180
                    $diff = abs($wd_loc - 180);
                    if ($diff < $lowest_value) {
                        $lowest_value = $diff;
                        $value = 5;
                    }

                    # 225
                    $diff = abs($wd_loc - 225);
                    if ($diff < $lowest_value) {
                        $lowest_value = $diff;
                        $value = 4;
                    }

                    # 270
                    $diff = abs($wd_loc - 270);
                    if ($diff < $lowest_value) {
                        $lowest_value = $diff;
                        $value = 3;
                    }

                    # 315
                    $diff = abs($wd_loc - 315);
                    if ($diff < $lowest_value) {
                        $lowest_value = $diff;
                        $value = 2;
                    }
                }
            }

            return "wind_" . $value;
        }
    }
    
    // Show what the moon phase for a certain date.
    private function moon_phase($year, $month, $day){

	$c = $e = $jd = $phase = $b = 0;

	if ($month < 3){
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

        if ($phase >= 8 ) $phase = 0;//0 and 8 are the same so turn 8 into 0
        
        switch($phase){
            case 0:
                $moonphase = array("phase"=>"New"); //New Moon
                break;
            case 1:
                $moonphase = array("phase"=>"Waxing"); //Waxing Cresent Moon
                break;
            case 2:
                $moonphase = array("phase"=>"Quarter"); //Queater Moon
                break;
            case 3:
                $moonphase = array("phase"=>"Waxing"); //Waxing Gibbous Moon
                break;
            case 4:
                $moonphase = array("phase"=>"Full"); //Waxing Gibbous Moon
                break;
            case 5:
                $moonphase = array("phase"=>"Waning"); //Waning Gibbous Moon
                break;
            case 6:
                $moonphase = array("phase"=>"Last"); //Last Quarter Moon
                break;
            case 7:
                $moonphase = array("phase"=>"Waning"); //Waning Cresent Moon
                break;
            default:
                $moonphase = array("phase"=>"Error");
                break;
                  
        }
        
        $moonphase['phase_code'] = $phase;
        
	return $moonphase;

    }
    
    private function popValArray($array, $val, $xdata = NULL, $ydata = NULL){
        foreach($array as $key=>$arr){
            foreach($arr as $ar){
                $arrSet[] = array(
                    'x' => (trim($xdata) != NULL)? $xdata : strtotime($ar['Datum'] . ' ' . $ar['utc'] . ':' . $ar['min']),
                    'y' => (trim($ydata) != NULL)? $ydata : $ar[$val],
                    'data' =>$ar[$val],
                    );
            }
        }
        return $arrSet;
    }
    
    private function highTemp($array){
        foreach($array as $arr){
            if(date('H', $arr['x']) == '20'){ $result[] = array(
                'x' => $arr['x'],
                'y' => $arr['data'],
                'data' => $arr['data'],
                );
            }
        }
        return $result;
    }
    
    private function LowTemp($array){
        foreach($array as $arr){
            if(date('H', $arr['x']) == '08'){ $result[] = array(
                'x' => $arr['x'],
                'y' => $arr['data'],
                'data' => $arr['data'],
                );
            }
        }
        return $result;
    }
    
    // Used to get the maximum and minimum in a set of array.
    private function amaxmin($array, $val, $arg){
        
        foreach($array as $key=>$arr){
            
            foreach($arr as $ar){
                if(!isset($arrmaxmin[$key])) {
                    if(trim($ar[$val])!='') {
                       $arrmaxmin[$key] = array(
                           'x' => strtotime($ar['Datum'] . ' ' . $ar['utc'] . ':' . $ar['min']),
                           'y' => $ar[$val],
                           'data' =>$ar[$val],
                           );
                       break;
                    }
                }
            }
            
            
            foreach($arr as $ar){
                if(trim($ar[$val])!=''){
                    if($arg == 'max' && $ar[$val]>=$arrmaxmin[$key]['y']){
                        $arrmaxmin[$key] = array(
                            'x' => strtotime($ar['Datum'] . ' ' . $ar['utc'] . ':' . $ar['min']),
                            'y' => trim($ar[$val]),
                            'data' => $ar[$val],
                        );
                        
                    }elseif($arg == 'min' && $ar[$val]<=$arrmaxmin[$key]['y']){
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
        foreach($arrmaxmin as $maxmin){
            $arrData[] = $maxmin;
        }
        
        return $arrData;
        
    }
    
}
