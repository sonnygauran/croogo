<?php

App::import('Lib', 'Meteomedia.Abfrage');
App::import('Lib', 'Meteomedia.Curl');
/**
 * Acquires the of Weather Forecast Stations
 */
class WeatherphStationForecast extends WeatherphAppModel
{
    
    public $name = 'WeatherphStationForecast';
    public $useTable = false;
    
    public function get($conditions = null, $fields = array(), $order = null, $recursive = null){
        
        $abfrageResults = array();
        
        $stationId = $fields['conditions']['id'];
        $Abfrage = new Abfrage($stationId);
        
        // Get the default timestamp timezone
        $siteTimezone = Configure::read('Site.timezone');
        $Date = new DateTime(null, new DateTimeZone($siteTimezone)); 
        
        $stationInfo = $this->getStationInfo($stationId, array("name","lat","lon"));
        
        $abfrageResults['station_name'] = $stationInfo['name'];
        
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
        $gum = $stationId.'_reading_'.sha1(end(explode('?',$url)));
        $curlResults = NULL;
        if (!Cache::read($gum, '3hour')) {
            $curlResults = Curl::getData($url);
            Cache::write($gum, $curlResults, '3hour');
        } else {
            $curlResults = Cache::read($gum, '3hour');
        }

        // Convert cURL CSV to Array 
        $resultsReadings = $this->csvToArray($curlResults);
        
        // Get sunrise and sunset using current latituted and longtitude station
        $sunrise = $this->sunInfo($stationInfo['lat'], $stationInfo['lon'], 'sunrise');
        $sunset = $this->sunInfo($stationInfo['lat'], $stationInfo['lon'], 'sunset');
        
        $today_Readings = $current_readings = array();
        
        foreach($resultsReadings as $readings){
            if($readings['tl']!=''){
                
                $current_readings['weather_symbol'] = $this->dayOrNightSymbol($readings['sy'], $readings['utc'], array("sunrise"=>$sunrise,"sunset"=>$sunset));
                             
                // Replace the null values with hypen character and round it off to the nearest tenths
                $current_readings['temperature'] = ($readings['tl'] == '')? '0' : number_format($readings['tl'],0);
                $current_readings['precipitation'] = ($readings['rr'] == '')? '0' : round($readings['rr'],0);
                $current_readings['relative_humidity'] = ($readings['rh'] == '')? '0' : round($readings['rh'],0);
                $current_readings['wind_speed'] = ($readings['ff'] == '')? '0' : floor($readings['ff'] * 1.852 + 0.5);
                $current_readings['wind_gust'] = ($readings['g3h'] == '')? '0' : round($readings['g3h'],0);
                
                // Set the local utc time
                $theirTime = strtotime($readings['Datum'] . $readings['utc'] . ':' .$readings['min']);
                
                $current_readings['Datum'] = date('Ymd', $theirTime + $Date->getOffset());
                $current_readings['utc'] = date('H', $theirTime + $Date->getOffset());
                $current_readings['min'] = date('m', $theirTime + $Date->getOffset());
                
                $current_readings['update'] = date('h:iA', $theirTime + $Date->getOffset());
                
                $today_Readings[] = $current_readings;
                
            }
        }
        
        // Get the last/current reading from the array set 
        $current_reading = array_pop($today_Readings);
        
        if(count($current_reading)>0){
            $abfrageResults['reading'] = $current_reading;
            $abfrageResults['reading']['status'] = 'ok';
        }else{
            $abfrageResults['reading']['status'] = 'none';
        }
                
        $headersSpecimen = "Datum;utc;min;ort1;dir;ff;g3h;tl;rr;rr1h;sy;rain3;rh;sy2;";
        
        //STATION FORECAST
        
        //Grab stations forecast  
        $url = $Abfrage->generateURL($this->generateDate('forecast', '3h'), array(
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
                'Period',
                '1 hour',
                '3 hours'
            ),
            'Weather Symbols' => array(
                'Set 1', 'Set 2'
            ),
            'Humidity'
        ));
        
        // Get the string after the question-mark sign
        $gum = $stationId.'_forecast_'.sha1(end(explode('?',$url)));
        $curlResults = NULL;
        if (!Cache::read($gum, '3hour')) {
            $curlResults = Curl::getData($url);
            Cache::write($gum, $curlResults, '3hour');
        } else {
            $curlResults = Cache::read($gum, '3hour');
        }
        
        // Convert cURL CSV results to Array 
        $resultsForecasts = $this->csvToArray($curlResults );
        
        // Get the current reading time for succeding forecast hours
        $nowHour = date('H', strtotime($current_reading['update']));
        
        $sum_rr1h = 0;
        
        foreach($resultsForecasts as $forecast){
            
            $utc_arr = explode(":", $forecast['utc']);
            
            if(trim($forecast['tl'])!=''){
                
                $sum_rr1h = $sum_rr1h + $forecast['rr1h'];
                
                if($utc_arr[0]%3 == 0){
                    
                    //Determine weather symbol based on sunrise and sunset
                    $current_forecast['weather_symbol'] = $this->dayOrNightSymbol($forecast['sy'], $forecast['utc'], array("sunrise"=>$sunrise,"sunset"=>$sunset));

                    // Replace the null values with hypen character and round it off to the nearest tenths
                    $current_forecast['temperature'] = ($forecast['tl'] == '')? '0' : number_format($forecast['tl'],0);
                    
                    if(trim($forecast['rain3']) == ""){
                        $current_forecast['precipitation'] = $sum_rr1h;
                        $sum_rr1h = 0;
                    }else{
                        $current_forecast['precipitation'] = $forecast['rain3'];
                    }
                    
                    //$current_forecast['rr'] = ($forecast['rr'] == '')? '0' : round($forecast['rr'],0);
                    $current_forecast['relative_humidity'] = ($forecast['rh'] == '')? '0' : round($forecast['rh'],0);
                    $current_forecast['wind_speed'] = ($forecast['ff'] == '')? '0' : floor($forecast['ff'] * 1.852 + 0.5); 
                    $current_forecast['wind_guts'] = ($forecast['g3h'] == '')? '0' : round($forecast['g3h'],0);

                    // Translate raw date to 3 hourly range value
                    $thierTime = strtotime($forecast['Datum'].' '.$forecast['utc'].':'.$forecast['min']);
                    $ourTime = $thierTime + $Date->getOffset();
                    
                    $current_forecast['localtime_range_end'] = date('Ymd H:i:s', $ourTime); 
                    $current_forecast['localtime_range'] = date('hA', strtotime('-3 hours', $ourTime)) .' - '. date('hA', $ourTime);

                    $readingTime = (!isset($current_reading['update']))? date('Ymd H:i:s') : $current_reading['update'];

                    if ($ourTime > strtotime($readingTime)) $abfrageResults['forecast'][] = $current_forecast;
                                       
                }
                
            }
        }
        
        if(count($abfrageResults['forecast'])>0){
           $abfrageResults['forecast']['status'] = 'ok'; 
        }else{
           $abfrageResults['forecast']['status'] = 'none'; 
        }
        
        //$this->log(print_r($abfrageResults, TRUE));
        
        return $abfrageResults;
        
    }
    
    private function forecastsDateOffsets(){
        
        //$date = strtotime('-'. Configure::read('Site.offset') + Configure::read('Site.time_overlap') . ' hours', strtotime(date('Ymd')));
        $date = strtotime('-'. Configure::read('Site.time_overlap') . ' hours');
        $start = date('Ymd H:i:s', $date);
        
        //$this->log(date('Ymd H:i:s', strtotime(date('Ymd H:i:s'))));
        
        $date = strtotime('+'. Configure::read('Site.offset'));
        $end = date('Ymd H:i:s', strtotime('+' . Configure::read('Site.forecast_range') . ' Days', $date));
        
        return array('startDate' => $start, 'endDate' => $end);
        
    }
    
    private function localTimeForecast($datasets = NULL){
        
        // Get the default timestamp timezone
        $siteTimezone = Configure::read('Site.timezone');
        $Date = new DateTime(null, new DateTimeZone($siteTimezone)); 
        
        //$this->log('Localtime' . print_r($datasets, TRUE));
        
        $new_datasets = array();
        
        $today = date('Ymd H:i:s' , strtotime(date('Ymd H:i:s')) + $Date->getOffset());
        
        foreach($datasets as $key => $dataset){
            
            foreach($dataset as $index => $data){
                
                $new_key = date('Ymd', strtotime($data['localtime']));
                
                //if(date('Ymd', strtotime($today)) == $new_key){
                    
                    if(strtotime($data['localtime_range_end']) >= strtotime($today)){
                        
                        $new_datasets[$new_key][] = $data;
                        
                    }
                    
                //}else{
                    
                //    $new_datasets[$new_key][] = $data;
                  
                //}
            }
            
        }
        
//        $this->log(print_r($new_datasets, TRUE));
        
        $newer_datasets = array();
        $dummy_datasets = $new_datasets;
        
        $skip = true;
        
        foreach($new_datasets as $index_date => $datasets){
            
            $next_date = date('Ymd', strtotime('+1 day', strtotime($index_date)));
            
            if(array_key_exists($next_date, $dummy_datasets)){
                
                $array_sets = $dummy_datasets[$next_date][0]; //
                
                array_push($datasets, $array_sets);
                
                if($skip == false) unset($datasets[0]);
                
                $skip = false;
                
                $newer_datasets[$index_date] = $datasets;
                
            }
            
            
            
        }
        
        $this->log(print_r($newer_datasets, TRUE));
        
        return $newer_datasets;
        
    }
    
    public function getWeeklyForecast($conditions = null, $fields = array(), $order = null, $recursive = null){
        
        $stationId = $fields['conditions']['id'];
        $Abfrage = new Abfrage($stationId);
        
        // Get the default timestamp timezone
        $siteTimezone = Configure::read('Site.timezone');
        $Date = new DateTime(null, new DateTimeZone($siteTimezone)); 
        
        // Set local UTC time based on the default timestamp timezone
        $dateOffsets = $this->forecastsDateOffsets();
        
        $this->log($dateOffsets);
        
        // Get station info based on id
        $stationInfo = $this->getStationInfo($stationId, array("name","lat","lon"));
        
        // STATION READINGS
        
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
                'Period','6 hours'
            ),
            'Weather Symbols' => array(
                'Set 1', 'Set 2'
            ),
            'Humidity'
        ));
        
        $this->log('Readings:'.$url);
        
        // Get the string after the question-mark sign
        $gum = $stationId.'_reading_weekly_'.sha1(end(explode('?',$url)));
        
        // Run the cURL
        $curlResults = NULL;
        if (!Cache::read($gum, '3hour')) {
            $e = new Exception();
            $curlResults = Curl::getData($url);
            Cache::write($gum, $curlResults, '3hour');
        } else {
            $curlResults = Cache::read($gum, '3hour');
        }
        
        // Convert cURL CSV to Array 
        $resultsReadings = $this->csvToArray($curlResults );
        
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
                
                $readings['dir2'] = $this->windDirection($readings['dir']);
                
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
        
        // Generate cURL for stations forecast  
        $url = $Abfrage->generateURL($this->generateDate('forecast', '1h'), array(
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
                'Period','1 hour', '3 hours', '6 hours'
            ),
            'Weather Symbols' => array(
                'Set 1', 'Set 2'
            ),
            'Humidity'
        ));
        
        //$this->log('Forecast'.$url);

        // Get the string after the question-mark sign
        $gum = $stationId.'_forecast_weekly_'.sha1(end(explode('?',$url)));
        
        $curlResults = NULL;
        if (!Cache::read($gum, '3hour')) {
            $e = new Exception();
            $curlResults = Curl::getData($url);
            Cache::write($gum, $curlResults, '3hour');
        } else {
            $curlResults = Cache::read($gum, '3hour');
        }
        
        $forecasts = $this->csvToArray($curlResults );
        
        $sum_rr1h = 0;
        
        foreach($forecasts as $forecast){
            
            $utc_arr = explode(":", $forecast['utc']);
            
            $new_forecast = array();
            
            $sum_rr1h = ($sum_rr1h + $forecast['rr1h']); 
            
            if(trim($forecast['tl'])!=''){

                if($utc_arr[0]%3 == 0){
                    
                    $new_forecast['Datum'] = $forecast['Datum'];
                    $new_forecast['utc'] = $forecast['utc'];
                    $new_forecast['min'] = $forecast['min'];
                    
                    $new_forecast['weather_symbol'] = $this->dayOrNightSymbol($forecast['sy'], $forecast['utc'], array("sunrise"=>$sunrise,"sunset"=>$sunset));

                    if(trim($forecast['rain3']) == ""){
                        $new_forecast['precipitation'] = $sum_rr1h;
                        $sum_rr1h = 0;
                    }else{
                        $new_forecast['precipitation'] = $forecast['rain3'];
                    }
                    
                    $new_forecast['relative_humidity'] = ($forecast['rh'] == '')? '0' : round($forecast['rh'],0);
                    $new_forecast['wind_speed'] = ($forecast['ff'] == '')? '0' : floor($forecast['ff'] * 1.852 + 0.5);
                    $new_forecast['wind_gust'] = ($forecast['g3h'] == '')? '0' : floor($forecast['g3h'] * 1.852 + 0.5);
                    $new_forecast['temperature'] = ($forecast['tl'] == '')? '0' : number_format($forecast['tl'],0); 

                    // Translate raw date to 3 hourly range value
                    $thierTime = strtotime($forecast['Datum'].' '.$forecast['utc'].':'.$forecast['min']);
                    
                    $new_forecast['their_time'] = date('Ymd H:i:s', $thierTime);
                    
                    $new_forecast['localtime'] = date('Ymd H:i:s', $thierTime + $Date->getOffset());
                    
                    $new_forecast['localtime_range_start'] = date('Ymd H:i:s', strtotime('-3 hours', $thierTime) + $Date->getOffset()); 
                    $new_forecast['localtime_range_end'] = date('Ymd H:i:s', $thierTime + $Date->getOffset());
                    
                    $new_forecast['localtime_range'] = date('hA', strtotime($new_forecast['localtime_range_start'])) . '-' . date('hA', strtotime($new_forecast['localtime_range_end']));

                    // Generate the wind description
                    $new_forecast['wind_description'] = $this->showWindDescription($forecast['dir'], $new_forecast['wind_speed'], $new_forecast['wind_gust']);
                    
                    // Translate raw data to wind direction image value
                    $new_forecast['wind_direction'] = $this->showWindDirection($forecast['dir']);
                    
                    //$this->log(print_r($new_forecast, TRUE));
                    
                    $abfrageResults['forecast'][$forecast['Datum']][] = $new_forecast;
                    
                }
                
            }
        }
        
//        $rain6_arr = array();
//        foreach($abfrageResults['forecast'] as $datum=>$forecasts){
//            foreach($forecasts as $utc=>$forecast){
//                $rain6_arr[] = $forecast['precipitation'];
//            }
//        }
//        
//        $rain6_arr = array_slice($rain6_arr,2);
//        
//        $cntr = 0;
//        foreach($abfrageResults['forecast'] as $datum=>$forecasts){
//            foreach($forecasts as $utc=>$forecast){
//                if($cntr < count($rain6_arr)){
//                    $abfrageResults['forecast'][$datum][$utc]['precipitation'] = number_format($rain6_arr[$cntr],1);
//                    $cntr++;
//                }
//            }
//        }
        
        $abfrageResults['forecast'] = $this->localTimeForecast($abfrageResults['forecast']);
        
        $abfrageResults['stationId'] = $stationId;
        $abfrageResults['stationName'] = $stationInfo['name'];
        
        return $abfrageResults;
        
    }
    
    public function getDetailedForecast($conditions = null, $fields = array(), $order = null, $recursive = null){
        
        // Get the default timestamp timezone
        $siteTimezone = Configure::read('Site.timezone');
        $Date = new DateTime(null, new DateTimeZone($siteTimezone)); 
        
        $stationId = $fields['conditions']['id'];
        $Abfrage = new Abfrage($stationId);
        $parameters = array();
        
        $type = $fields['conditions']['type'];
        $timeRes = ($fields['conditions']['timeRes'] == NULL )? '1h' : $fields['conditions']['timeRes'];
        
        switch($type){
            case 'temperature':
            case 'temp':
                $parameters = array(
                    'Temperature' => array(
                        'low', 'min', 'max', 'dew point'
                    )
                );
                //$data_time_resolution = '3h'; 
                break;
            case 'humidity':
                $parameters = array(
                    'Humidity'
                );
                //$data_time_resolution = '3h';
                break;
            case 'precipitation':
            case 'precip':
                $parameters = array(
                    'Rainfall' => array(
                        '6 hours'
                     )
                );
                //$data_time_resolution = '6h';
                break;
            case 'wind':
                $parameters = array(
                    'Wind' => array(
                        'speed'
                    ),
                    'Gust' => array(
                        '1 hour'
                    )
                );
                //$data_time_resolution = '3h';
                break;
            case 'dir':
            case 'winddir':
                $parameters = array(
                    'Wind' =>array(
                        'direction'
                    )
                );
                //$data_time_resolution = '3h';
                break;
        }
        
        //$timeRes = $data_time_resolution;
        
        $url = $Abfrage->generateURL($this->generateDate('chart', $timeRes), $parameters);
        
        
        $gum = $stationId.'_detailed_forecast_'.sha1(end(explode('?',$url)));
        $curlResults = NULL;
        if (!Cache::read($gum, '3hour')) {
            $curlResults = Curl::getData($url);
            Cache::write($gum, $curlResults, '3hour');
        } else {
            $curlResults = Cache::read($gum, '3hour');
        }
        
        $results = $this->csvToArray($curlResults );
        
        foreach($results as $result){
            
            //explode the ort1 raw data, grab only those needed
            $result['ort1'] = explode('/', $result['ort1']);
            unset($result['ort1'][0]);
            $result['ort1'] = implode('/', $result['ort1']);

            $abfrageResults['ort1'] = $result['ort1']; 

            $utcDate = strtotime($result['Datum'] . $result['utc'] . ':' .$result['min']) + $Date->getOffset();
            
            $result['Datum'] = date('Ymd', $utcDate);
            $result['utc'] = date('H', $utcDate);
            $result['min'] = date('m', $utcDate);

            $abfrageResults['forecast'][$result['Datum']][] = $result;

       
        }
                
        //$this->log(print_r($abfrageResults, true));
        
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
                        'show_cross_label' => 'True',
                        'default_series_type' => 'Spline',
                        );
                    $resultData['series'] = array(
                        'tl' => array('name'=>'tlseries', 'style'=>'tlline', 'use_hand_cursor'=>'False', 'hoverable'=>'False'),
                        'td' => array('name'=>'tdseries', 'style'=>'tdline', 'use_hand_cursor'=>'False', 'hoverable'=>'False'),
                        'tx' => array('name'=>'txseries', 'style'=>'noline', 'use_hand_cursor'=>'True', 'hoverable'=>'False'),
                        'tn' => array('name'=>'tnseries', 'style'=>'noline', 'use_hand_cursor'=>'True', 'hoverable'=>'False'),
                    );
                    $resultData['additional'] = array(
                        'tl' => array('tooltip' => array('enabled' => 'True')),
                        'td' => array('tooltip' => array('enabled' => 'True')),
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
    
    public function dmoForecast($condition = NULL, $fields = array()){
        
        App::import('Model', 'Nima.NimaName');
        
        // Get the default timestamp timezone
        $siteTimezone = Configure::read('Site.timezone');
        $Date = new DateTime(null, new DateTimeZone($siteTimezone));
        
        $NimaName = new NimaName();
        $station_id = $fields['conditions']['id']; 
        $stationInfo = $NimaName->find('all', array('fields' => array('id' ,'lat', 'long', 'full_name_ro'),  'conditions' => array( 'id =' => $station_id)));
        $stationInfo = $stationInfo[0]['Name'];
        
        $nearestGP = $this->nearestGridPoint($stationInfo['long'],$stationInfo['lat']);
        
        $this->log(print_r($nearestGP, TRUE));
        
        $dmo_forecast_dir = Configure::read('Data.dmo');
        
        $dmo_forecast = $dmo_forecast_dir . $nearestGP['lon'] . '_' . $nearestGP['lat'] . '.csv';
        
        $csvString = file_get_contents($dmo_forecast);
        
        $forecasts = $this->csvToArray($csvString);
        
        //$this->log(print_r($forecasts, TRUE));
        
        // Get sunrise and sunset using current latituted and longtitude station
        $sunrise = $this->sunInfo($stationInfo['lat'], $stationInfo['long'], 'sunrise');
        $sunset = $this->sunInfo($stationInfo['lat'], $stationInfo['long'], 'sunset');
        
        $dmoResults['reading'] = '';
        $dmoResults['reading']['status'] = 'none';
        $dmoResults['reading']['sunrise'] = $sunrise;
        $dmoResults['reading']['sunset'] = $sunset;
        
        foreach($forecasts as $forecast){
            
            $new_forecast = array();
            
            if(trim($forecast['tl'])!=''){
                    
                    $new_forecast['Datum'] = $forecast['Datum'];
                    $new_forecast['utc'] = $forecast['utc'];
                    $new_forecast['min'] = $forecast['min'];
                    
                    $new_forecast['weather_symbol'] = (trim($forecast['sy']) == '')? '0' : $this->dayOrNightSymbol($forecast['sy'], $forecast['utc'], array("sunrise"=>$sunrise,"sunset"=>$sunset));

                    $new_forecast['precipitation'] = ($forecast['rain3'] == '')? '0' : number_format($forecast['rain3'],1);
                    $new_forecast['relative_humidity'] = ($forecast['rh'] == '')? '0' : round($forecast['rh'],0);
                    $new_forecast['wind_speed'] = ($forecast['ff'] == '')? '0' : floor($forecast['ff'] * 1.852 + 0.5);
                    $new_forecast['wind_gust'] = ($forecast['g6h'] == '')? '0' : floor($forecast['g6h'] * 1.852 + 0.5);
                    $new_forecast['temperature'] = ($forecast['tl'] == '')? '0' : number_format($forecast['tl'],0); 

                    // Translate raw date to 3 hourly range value
                    $thierTime = strtotime($forecast['Datum'].' '.$forecast['utc'].':'.$forecast['min']);
                    
                    $new_forecast['their_time'] = date('Ymd H:i:s', $thierTime);
                    
                    $new_forecast['localtime'] = date('Ymd H:i:s', $thierTime + $Date->getOffset());
                    
                    $new_forecast['localtime_range_start'] = date('Ymd H:i:s', strtotime('-3 hours', $thierTime) + $Date->getOffset()); 
                    $new_forecast['localtime_range_end'] = date('Ymd H:i:s', $thierTime + $Date->getOffset());
                    
                    $new_forecast['localtime_range'] = date('hA', strtotime($new_forecast['localtime_range_start'])) . '-' . date('hA', strtotime($new_forecast['localtime_range_end']));

                    // Generate the wind description
                    $new_forecast['wind_description'] = $this->showWindDescription($forecast['dir'], $new_forecast['wind_speed'], $new_forecast['wind_gust']);
                    
                    // Translate raw data to wind direction image value
                    $new_forecast['wind_direction'] = $this->showWindDirection($forecast['dir']);
                    
                    $dmoResults['forecast'][$forecast['Datum']][] = $new_forecast;
                
            }
        }
        
        //$this->log(print_r($dmoResults, TRUE));
        
        $dmoResults['forecast'] = $this->localTimeForecast($dmoResults['forecast']);
        
        $dmoResults['stationId'] = $station_id;
        $dmoResults['stationName'] = $stationInfo['full_name_ro'];
        
        //$this->log(print_r($dmoResults, TRUE));
        
        return $dmoResults;
        
    }
    
    private function nearestGridPoint($lon, $lat){
        
        $schrittx = 0.125;
        $schritty = 0.125;
        
        $nearest_lon = round($lon/$schrittx)*$schrittx;
        $nearest_lat = round($lat/$schritty)*$schritty;
        
        return array(
            'lon' => $nearest_lon,
            'lat' => $nearest_lat
        );
        
    }
    
}