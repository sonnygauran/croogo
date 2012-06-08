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
        
        $abfrageResults = array();
        
        $stationId = $fields['conditions']['id'];
        
        // Get the default timestamp timezone
        $siteTimezone = Configure::read('Site.timezone');
        $Date = new DateTime(null, new DateTimeZone($siteTimezone)); 
        
        // Set local UTC time based on the default timestamp timezone
        $localutctimestart = date('Ymd H:i:s', strtotime('-'.($Date->getOffset()/3600).' hours', strtotime(date('Ymd'))));
        $localutctimeend = date('Ymd H:i:s', strtotime('+2 Day', strtotime($localutctimestart)));
        
        // Get the start date from local UTC time
        $startdatum = date('Ymd', strtotime($localutctimestart));
        $startutc = date('H', strtotime($localutctimestart));
        
        // Get the end date from local UTC time
        $enddatum = date('Ymd', strtotime($localutctimeend));
        $endutc = date('H', strtotime($localutctimeend));
        
        $stationInfo = $this->getStationInfo($stationId, array("name","lat","lon"));
        
        $abfrageResults['station_name'] = $stationInfo['name'];
        
        // Set the target header specimen 
        $headersSpecimen = "Datum;utc;min;ort1;dir;ff;g3h;tl;rr;sy;rh;sy2;";
        
        //Grab stations readings  
        $url = "http://192.168.20.89/abfrage.php?stationidstring=$stationId&datumstart=$startdatum&datumend=$enddatum&utcstart=$startutc&utcend=$endutc&zeiten1=10m&paramliste=tl,dir,ff,g3h,rr,rh,sy,sy2&output=csv2&ortoutput=wmo6,name&aufruf=auto";
        //$this->log($url);
        
        // Get the string after the question-mark sign
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
        
        // Convert cURL CSV to Array 
        $resultsReadings = $this->csvToArray($curlResults, $headersSpecimen);
        
        // Get sunrise and sunset using current latituted and longtitude station
        $sunrise = $this->sunInfo($stationInfo['lat'], $stationInfo['lon'], 'sunrise');
        $sunset = $this->sunInfo($stationInfo['lat'], $stationInfo['lon'], 'sunset');
        
        $currentReadings = array();
        foreach($resultsReadings as $readings){
            if($readings['tl']!=''){
                
                //Determine weather symbol for certain utc time
                $readings['sy'] = $this->dayOrNightSymbol($readings['sy'], $readings['utc'], array("sunrise"=>$sunrise,"sunset"=>$sunset));
                
                // Replace the null values with hypen character and round it off to the nearest tenths
                $readings['tl'] = ($readings['tl'] == '')? '0' : number_format($readings['tl'],0);
                $readings['rr'] = ($readings['rr'] == '')? '0' : round($readings['rr'],0);
                $readings['rh'] = ($readings['rh'] == '')? '0' : round($readings['rh'],0);
                $readings['ff'] = ($readings['ff'] == '')? '0' : floor($readings['ff'] * 1.852 + 0.5);
                $readings['g3h'] = ($readings['g3h'] == '')? '0' : round($readings['g3h'],0);
                
                // Translate raw data to wind direction image value
                $readings['dir'] = $this->showWindDirection($readings['dir']);
                
                // Set the local utc time
                $theirTime = strtotime($readings['Datum'] . $readings['utc'] . ':' .$readings['min']);
                $readings['Datum'] = date('Ymd', $theirTime + $Date->getOffset());
                $readings['utc'] = date('H', $theirTime + $Date->getOffset());
                $readings['min'] = date('m', $theirTime + $Date->getOffset());
                
                $readings['update'] = date('h:iA', $theirTime + $Date->getOffset());
                
                $currentReadings[] = $readings;
                
            }
        }
        
        // Get the last/current reading from the array set 
        $currentReading = array_pop($currentReadings);
        
        if(count($currentReading)>0){
            $abfrageResults['reading'] = $currentReading;
            $abfrageResults['reading']['status'] = 'ok';
        }else{
            $abfrageResults['reading']['status'] = 'none';
        }
        
        //STATION FORECAST
        
        //Grab stations forecast  
        $url = "http://192.168.20.89/abfrage.php?stationidstring=$stationId&datumstart=$startdatum&datumend=$enddatum&utcstart=$startutc&utcend=$endutc&zeiten1=3h&paramtyp=mos_mix_mm&&paramliste=tl,dir,ff,g3h,rr,rh,sy,sy2&output=csv2&ortoutput=wmo6,name&aufruf=auto";
        
        // Get the string after the question-mark sign
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
        
        // Convert cURL CSV results to Array 
        $resultsForecasts = $this->csvToArray($curlResults, $headersSpecimen);
        
        // Get the current reading time for succeding forecast hours
        $nowHour = date('H', strtotime($currentReading['update']));
        $nowHourRound = $nowHour - ($nowHour % 3);
        
        $hourStart = false;
        
        $abfrageResults['forecast'] = array();
        foreach($resultsForecasts as $forecast){
            
            if(trim($forecast['tl'])!=''){
                
                //Determine weather symbol based on sunrise and sunset
                $forecast['sy'] = $this->dayOrNightSymbol($forecast['sy'], $forecast['utc'], array("sunrise"=>$sunrise,"sunset"=>$sunset));
                
                // Replace the null values with hypen character and round it off to the nearest tenths
                $forecast['tl'] = ($forecast['tl'] == '')? '0' : number_format($forecast['tl'],0);
                $forecast['rr'] = ($forecast['rr'] == '')? '0' : round($forecast['rr'],0);
                $forecast['rh'] = ($forecast['rh'] == '')? '0' : round($forecast['rh'],0);
                $forecast['ff'] = ($forecast['ff'] == '')? '0' : floor($forecast['ff'] * 1.852 + 0.5); 
                $forecast['g3h'] = ($forecast['g3h'] == '')? '0' : round($forecast['g3h'],0);
                
                // Translate raw date to 3 hourly range value
                $thierTime = strtotime($forecast['Datum'].' '.$forecast['utc'].':'.$forecast['min']);
                $ourTime = $thierTime + $Date->getOffset();
                $forecast['utch'] = date('hA', $ourTime) . ' - ' . date('hA', strtotime('+3 hours', $ourTime)) ;
                
                // Translate raw data to wind direction image value
                $forecast['dir'] = $this->showWindDirection($forecast['dir']);
                
                $readingTime = (!isset($currentReading['update']))? date('Ymd H:i:s') : $currentReading['update'];
                                
                if ($ourTime > strtotime($readingTime)) {
                    $abfrageResults['forecast']['status'] = 'ok';
                    $abfrageResults['forecast'][] = $forecast;
                }else{
                    $abfrageResults['forecast']['status'] = 'none';
                }
                
            }
        }
        
        return $abfrageResults;
        
    }
    
    private function forecastsDateOffsets(){
        
        $date = strtotime('-'. Configure::read('Site.offset') + Configure::read('Site.time_overlap') . ' hours', strtotime(date('Ymd')));
        $start = date('Ymd H:i:s', $date);
        
        $date = strtotime('+'. Configure::read('Site.offset') + Configure::read('Site.time_overlap') . ' hours', strtotime($start));
        $end = date('Ymd H:i:s', strtotime('+' . Configure::read('Site.forecast_range') . ' Days', $date));
        
        return array('startDate' => $start, 'endDate' => $end);
        
    }
    
    private function localTimeForecast($datasets = NULL){
        
        // Get the default timestamp timezone
        $siteTimezone = Configure::read('Site.timezone');
        $Date = new DateTime(null, new DateTimeZone($siteTimezone)); 
        
        $new_datasets = array();
        
        $today = date('Ymd H:i:s' , strtotime(date('Ymd H:i:s')) + $Date->getOffset());
        
        foreach($datasets as $key=>$dataset){
            foreach($dataset as $data){
                
                $new_key = date('Ymd', strtotime($data['localtime']));
                
                if(date('Ymd', strtotime($today)) == $new_key){
                    
                    if(strtotime($data['localtime_range_end']) >= strtotime($today)){
                        
                        $new_datasets[$new_key][] = $data;
                        
                    }
                    
                }else
                    $new_datasets[$new_key][] = $data;
            }
            
        }
        
        return $new_datasets;
        
    }
    
    public function getWeeklyForecast($conditions = null, $fields = array(), $order = null, $recursive = null){
        
        include dirname(__FILE__) . '/auth.php';
        
        $stationId = $fields['conditions']['id'];
        
        // Get the default timestamp timezone
        $siteTimezone = Configure::read('Site.timezone');
        $Date = new DateTime(null, new DateTimeZone($siteTimezone)); 
        
        // Set local UTC time based on the default timestamp timezone
        $dateOffsets = $this->forecastsDateOffsets();
        
        // Get the start date from local UTC time
        $startdatum = date('Ymd', strtotime($dateOffsets['startDate']));
        $startutc = date('H', strtotime($dateOffsets['startDate']));
        
        // Get the end date from local UTC time
        $enddatum = date('Ymd', strtotime('+1 Day', strtotime($dateOffsets['startDate'])));
        $endutc = date('H', strtotime($dateOffsets['endDate']));
        
        // Get station info based on id
        $stationInfo = $this->getStationInfo($stationId, array("name","lat","lon"));
        
        // STATION READINGS
        
        //Grab stations readings  
        $url = "http://192.168.20.89/abfrage.php?stationidstring=$stationId&datumstart=$startdatum&datumend=$enddatum&utcstart=$startutc&utcend=$endutc&zeiten1=10m&paramliste=tl,dir,ff,g3h,rr,rh,sy,sy2,rain6&output=csv2&ortoutput=wmo6,name&aufruf=auto";
        //$this->log($url);
        
        // Get the string after the question-mark sign
        $gum = $stationId.'_reading_weekly_'.sha1(end(explode('?',$url)));
        
        // Run the cURL
        $curlResults = NULL;
        if (!Cache::read($gum, '3hour')) {
            $e = new Exception();
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
        
        // Set the target header specimen
        $headersSpecimen = "Datum;utc;min;ort1;dir;ff;g3h;tl;rr;sy;rain6;rh;sy2;";
        
        // Convert cURL CSV to Array 
        $resultsReadings = $this->csvToArray($curlResults, $headersSpecimen);
        
        // Get sunrise and sunset using current latituted and longtitude station
        $sunrise = $this->sunInfo($stationInfo['lat'], $stationInfo['lon'], 'sunrise');
        $sunset = $this->sunInfo($stationInfo['lat'], $stationInfo['lon'], 'sunset');
        
        $currentReadings = array();
        foreach($resultsReadings as $readings){
            if($readings['tl']!=''){
                
                //Determine weather symbol for certain utc time
                $readings['sy'] = $this->dayOrNightSymbol($readings['sy'], $readings['utc'], array("sunrise"=>$sunrise,"sunset"=>$sunset));
                
                // Replace the null values with hypen character and round it off to the nearest tenths
                $readings['tl'] = ($readings['tl'] == '')? '0' : number_format($readings['tl'],0);
                $readings['rr'] = ($readings['rr'] == '')? '0' : round($readings['rr'],0);
                $readings['rh'] = ($readings['rh'] == '')? '0' : round($readings['rh'],0);
                $readings['ff'] = ($readings['ff'] == '')? '0' : floor($readings['ff'] * 1.852 + 0.5);
                $readings['g3h'] = ($readings['g3h'] == '')? '0' : round($readings['g3h'],0);
                
                $theirTimestamp = strtotime($readings['Datum'] . $readings['utc'] . ':' . $readings['min']);
                
                $readings['moonphase'] = $this->moon_phase(date('Y', strtotime($readings['Datum'])), date('m', strtotime($readings['Datum'])), date('d', strtotime($readings['Datum'])));
                
                // Translate raw data to wind direction image value
                $readings['dir'] = $this->showWindDirection($readings['dir']);
                
                $readings['localtime'] = date('Ymd H:i:s', $theirTimestamp + $Date->getOffset());

                $currentReadings[] = $readings;
                
            }
        }
        
        // Get the last/current reading from the array set
        $currentReading = array_pop($currentReadings);
        
        // Check if the last/current reading exist, and set status
        if(count($currentReading)>0){
            $abfrageResults['reading'] = $currentReading;
            $abfrageResults['reading']['status'] = 'ok';
            $abfrageResults['reading']['sunrise'] = $sunrise;
            $abfrageResults['reading']['sunset'] = $sunset;
            
        }else{
            $abfrageResults['reading']['status'] = 'none';
        }
        
        // STATION FORECAST
        
        $startdatum = date('Ymd', strtotime($dateOffsets['startDate']));
        $startutc = date('H', strtotime($dateOffsets['startDate']));
        
        $enddatum = date('Ymd', strtotime($dateOffsets['endDate']));
        $endutc = date('H', strtotime($dateOffsets['endDate']));
        
        // Generate cURL for stations forecast  
        $url = "http://192.168.20.89/abfrage.php?stationidstring=$stationId&datumstart=$startdatum&datumend=$enddatum&utcstart=$startutc&utcend=$endutc&zeiten1=1h&paramtyp=mos_mix_mm&mosmess=nein&paramliste=tl,dir,ff,g3h,rr,rh,sy,sy2,rain6&output=csv2&ortoutput=wmo6,name&timefill=nein&verknuepft=nein&aufruf=auto";
        //$this->log($url);
        
        // Get the string after the question-mark sign
        $gum = $stationId.'_forecast_weekly_'.sha1(end(explode('?',$url)));
        
        $curlResults = NULL;
        if (!Cache::read($gum, '3hour')) {
            $e = new Exception();
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
        
        $headersSpecimen = "Datum;utc;min;ort1;dir;ff;g3h;tl;rr;sy;rain6;rh;sy2;";
        
        $forecasts = $this->csvToArray($curlResults, $headersSpecimen);
        
        foreach($forecasts as $forecast){
            
            $utc_arr = explode(":", $forecast['utc']);
        
            if(trim($forecast['tl'])!=''){

                if($utc_arr[0]%3 == 0){
                    
                    // Determine the weather symbol for certain UTC time
                    $forecast['sy'] = $this->dayOrNightSymbol($forecast['sy'], $forecast['utc'], array("sunrise"=>$sunrise,"sunset"=>$sunset));

                    // Replace the NULL values with hypen character and do roundings
                    $forecast['rain6'] = ($forecast['rain6'] == "")? '0' : round($forecast['rain6'],1);
                    $forecast['rh'] = ($forecast['rh'] == '')? '0' : round($forecast['rh'],0);
                    $forecast['ff'] = ($forecast['ff'] == '')? '0' : floor($forecast['ff'] * 1.852 + 0.5);
                    $forecast['g3h'] = ($forecast['g3h'] == '')? '0' : floor($forecast['g3h'] * 1.852 + 0.5);
                    $forecast['tl'] = ($forecast['tl'] == '')? '0' : number_format($forecast['tl'],0); 

                    // Translate raw date to 3 hourly range value
                    $thierTime = strtotime($forecast['Datum'].' '.$forecast['utc'].':'.$forecast['min']);
                    
                    $forecast['localtime'] = date('Ymd H:s:s', $thierTime + $Date->getOffset());
                    $forecast['localtime_range_start'] = date('Ymd H:i:s', $thierTime + $Date->getOffset()); 
                    $forecast['localtime_range_end'] = date('Ymd H:i:s', strtotime('+3 hours', $thierTime) + $Date->getOffset());
                    $forecast['localtime_range'] = date('hA', strtotime($forecast['localtime_range_start'])) . '-' . date('hA', strtotime($forecast['localtime_range_end']));

                    // Generate the wind description
                    $forecast['windDesc'] = $this->showWindDescription($forecast['dir'], $forecast['ff']);
                    
                    // Translate raw data to wind direction image value
                    $forecast['dir'] = $this->showWindDirection($forecast['dir']);
                   
                    $abfrageResults['forecast'][$forecast['Datum']][] = $forecast;
//                  
                }

            }
        }
        
        $rain6_arr = array();
        foreach($abfrageResults['forecast'] as $datum=>$forecasts){
            foreach($forecasts as $utc=>$forecast){
                $rain6_arr[] = $forecast['rain6'];
            }
        }
        
        $rain6_arr = array_slice($rain6_arr,2);
        
        $cntr = 0;
        foreach($abfrageResults['forecast'] as $datum=>$forecasts){
            foreach($forecasts as $utc=>$forecast){
                if($cntr < count($rain6_arr)){
                    $abfrageResults['forecast'][$datum][$utc]['rain6'] = number_format($rain6_arr[$cntr],1);
                    $cntr++;
                }
            }
        }
        
        $abfrageResults['forecast'] = $this->localTimeForecast($abfrageResults['forecast']);
        
        //$this->log(print_r($abfrageResults['forecast'],true));
        
        $abfrageResults['stationId'] = $stationId;
        $abfrageResults['stationName'] = $stationInfo['name'];
        
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
        
        $gum = $stationId.'_detailed_forecast_'.sha1(end(explode('?',$url)));
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
}
