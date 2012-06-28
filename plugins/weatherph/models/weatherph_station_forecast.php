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
        
        ini_set('memory_limit','128M');
        
        $abfrageResults = array();
        
        $stationId = $fields['conditions']['id'];
        
        $dmo_readings_dir = Configure::read('Data.readings');
        $dmo_readings_file = $dmo_readings_dir . date('Ydm') . '.csv';
        
        $stationInfo = $this->getStationInfo($stationId);
        
        if(file_exists($dmo_readings_file)){
            
            $this->log('File found - ' . $dmo_readings_file);
            
            $csvString = file_get_contents($dmo_readings_file);
            $dmo_readings = $this->csvToArray($csvString);
            $dmo_readings = $this->cleanDmoReadings($dmo_readings);

            // Get the default timestamp timezone
            $siteTimezone = Configure::read('Site.timezone');
            $Date = new DateTime(null, new DateTimeZone($siteTimezone)); 

            $abfrageResults['station_name'] = $stationInfo['name'];

            // Get sunrise and sunset using current latituted and longtitude station
            $sunrise = $this->sunInfo($stationInfo['lat'], $stationInfo['lon'], 'sunrise');
            $sunset = $this->sunInfo($stationInfo['lat'], $stationInfo['lon'], 'sunset');

            $today_Readings = $current_readings = array();

            foreach($dmo_readings as $dmo_reading){

                if(in_array($stationId, $dmo_reading)){

                    $current_readings['weather_symbol'] = (trim($dmo_reading['sy']) == '')? '0' : $this->dayOrNightSymbol($dmo_reading['sy'], $dmo_reading['utc'], array("sunrise"=>$sunrise,"sunset"=>$sunset));

                    // Replace the null values with hypen character and round it off to the nearest tenths
                    $current_readings['temperature'] = ($dmo_reading['tl'] == '')? '0' : number_format($dmo_reading['tl'],0);
                    $current_readings['precipitation'] = ($dmo_reading['rr'] == '')? '0' : round($dmo_reading['rr'],0);
                    $current_readings['relative_humidity'] = ($dmo_reading['rh'] == '')? '0' : round($dmo_reading['rh'],0);
                    $current_readings['wind_speed'] = ($dmo_reading['ff'] == '')? '0' : floor($dmo_reading['ff'] * 1.852 + 0.5);
                    $current_readings['wind_gust'] = ($dmo_reading['g3h'] == '')? '0' : round($dmo_reading['g3h'],0);


                    $theirTime = strtotime($dmo_reading['Datum'] . $dmo_reading['utc'] . ':' .$dmo_reading['min']);
                    //$current_time = strtotime(date('Ymd H:i:s')) + $Date->getOffset();
                    $current_readings['local_time'] = date('Ymd H:i:s', $theirTime + $Date->getOffset());
                    $current_readings['update'] = date('h:iA', $theirTime + $Date->getOffset());

                    //if(strtotime($dmo_reading['local_time']) > $current_time ) 
                    $today_Readings[] = $current_readings;

                }

            }

            $current_reading = array_pop($today_Readings);
            
        }else{
            $this->log('File not found - ' . $dmo_readings_file);
            $current_reading = array();
            //exit;
        }
        
//        $this->log(print_r($current_reading, TRUE));
     
        if(count($current_reading)>0){
            $abfrageResults['reading'] = $current_reading;
            $abfrageResults['reading']['status'] = 'ok';
        }else{
            $abfrageResults['reading']['status'] = 'none';
        }
                
        $headersSpecimen = "Datum;utc;min;ort1;dir;ff;g3h;tl;rr;rr1h;sy;rain3;rh;sy2;";
        
        $nearestGP = $this->nearestGridPoint($stationInfo['lon'],$stationInfo['lat']);
        
        $dmo_forecast_dir = Configure::read('Data.dmo');
        $dmo_forecast_file = $dmo_forecast_dir . $nearestGP['lon'] . '_' . $nearestGP['lat'] . '.csv';
        
        if(file_exists($dmo_forecast_file)){ 
            
            $this->log('File found - ' . $dmo_forecast_file);
            
            $csvString = file_get_contents($dmo_forecast_file);
            
            $resultsForecasts = $this->csvToArray($csvString);
        
            foreach($resultsForecasts as $forecast){

                $current_forecast = array();

                if(trim($forecast['tl'])!=''){

                        $current_forecast['Datum'] = $forecast['Datum'];
                        $current_forecast['utc'] = $forecast['utc'];
                        $current_forecast['min'] = $forecast['min'];

                        $current_forecast['weather_symbol'] = (trim($forecast['sy']) == '')? '0' : $this->dayOrNightSymbol($forecast['sy'], $forecast['utc'], array("sunrise"=>$sunrise,"sunset"=>$sunset));

                        $current_forecast['precipitation'] = ($forecast['rain3'] == '')? '0' : number_format($forecast['rain3'],1);
                        $current_forecast['relative_humidity'] = ($forecast['rh'] == '')? '0' : round($forecast['rh'],0);
                        $current_forecast['wind_speed'] = ($forecast['ff'] == '')? '0' : floor($forecast['ff'] * 1.852 + 0.5);
                        $current_forecast['wind_gust'] = ($forecast['g6h'] == '')? '0' : floor($forecast['g6h'] * 1.852 + 0.5);
                        $current_forecast['temperature'] = ($forecast['tl'] == '')? '0' : number_format($forecast['tl'],0); 

                        // Translate raw date to 3 hourly range value
                        $thierTime = strtotime($forecast['Datum'].' '.$forecast['utc'].':'.$forecast['min']);

                        $current_forecast['their_time'] = date('Ymd H:i:s', $thierTime);

                        $current_forecast['localtime'] = date('Ymd H:i:s', $thierTime + $Date->getOffset());

                        $current_forecast['localtime_range_start'] = date('Ymd H:i:s', strtotime('-3 hours', $thierTime) + $Date->getOffset()); 
                        $current_forecast['localtime_range_end'] = date('Ymd H:i:s', $thierTime + $Date->getOffset());

                        $current_forecast['localtime_range'] = date('hA', strtotime($current_forecast['localtime_range_start'])) . '-' . date('hA', strtotime($current_forecast['localtime_range_end']));

                        // Generate the wind description
                        $current_forecast['wind_description'] = $this->showWindDescription($forecast['dir'], $current_forecast['wind_speed'], $current_forecast['wind_gust']);

                        $readingTime = date('Ymd H:i:s', strtotime(date('Ymd H:i:s')) + $Date->getOffset());

                        if (strtotime($current_forecast['localtime']) > strtotime($readingTime)) $abfrageResults['forecast'][] = $current_forecast;

                }

            }
            
            $abfrageResults['forecast_dmo_file_csv'] = $nearestGP['lon'] . '_' . $nearestGP['lat'] . '.csv';
            
        }else{
            $this->log('File not found - ' . $dmo_forecast_file);
            //exit;
        }
        
        if(key_exists('forecast', $abfrageResults) AND count($abfrageResults['forecast'])>0){
           $abfrageResults['forecast']['status'] = 'ok'; 
        } else {
           $abfrageResults['forecast']['status'] = 'none'; 
        }
        
        return $abfrageResults;
        
    }
    
    private function forecastsDateOffsets(){
        
        $date = strtotime('-'. Configure::read('Site.time_overlap') . ' hours');
        $start = date('Ymd H:i:s', $date);
        
        $date = strtotime('+'. Configure::read('Site.offset'));
        $end = date('Ymd H:i:s', strtotime('+' . Configure::read('Site.forecast_range') . ' Days', $date));
        
        return array('startDate' => $start, 'endDate' => $end);
        
    }
    
    private function localTimeForecast($datasets = NULL){
        
        // Get the default timestamp timezone
        $siteTimezone = Configure::read('Site.timezone');
        $Date = new DateTime(null, new DateTimeZone($siteTimezone)); 
        
//        $this->log('Localtime' . print_r($datasets, TRUE));
        
        $new_datasets = array();
        
        $today = date('Ymd H:i:s' , strtotime(date('Ymd H:i:s')) + $Date->getOffset());
        
        foreach($datasets as $key => $dataset){
            
            foreach($dataset as $index => $data){
                
                $new_key = date('Ymd', strtotime($data['localtime']));
                    
                if(strtotime($data['localtime_range_end']) >= strtotime($today)){

                    $new_datasets[$new_key][] = $data;

                }
                
            }
            
        }
        
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
        
        return $newer_datasets;
        
    }
    
    public function getWeeklyForecast($conditions = null, $fields = array(), $order = null, $recursive = null){
        
        ini_set('memory_limit','128M');
        
        $stationId = $fields['conditions']['id'];
        
        // Get the default timestamp timezone
        $siteTimezone = Configure::read('Site.timezone');
        $Date = new DateTime(null, new DateTimeZone($siteTimezone)); 
        
        // Get station info based on id
        $stationInfo = $this->getStationInfo($stationId, array("name","lat","lon"));
        
        $dmo_readings_dir = Configure::read('Data.readings');
        $dmo_readings_file = $dmo_readings_dir . date('Ydm') . '.csv';
        
        if(file_exists($dmo_readings_file)){ 
            
            $this->log('File found - ' . $dmo_readings_file);
            
            $csvString = file_get_contents($dmo_readings_file);
            $dmo_readings = $this->csvToArray($csvString);
            $dmo_readings = $this->cleanDmoReadings($dmo_readings);

            // Get sunrise and sunset using current latituted and longtitude station
            $sunrise = $this->sunInfo($stationInfo['lat'], $stationInfo['lon'], 'sunrise');
            $sunset = $this->sunInfo($stationInfo['lat'], $stationInfo['lon'], 'sunset');

            $today_Readings = $current_readings = array();

            foreach($dmo_readings as $dmo_reading){

                if(in_array($stationId, $dmo_reading)){

                    $current_readings['moonphase'] = $this->moon_phase(date('Y', strtotime($dmo_reading['Datum'])), date('m', strtotime($dmo_reading['Datum'])), date('d', strtotime($dmo_reading['Datum'])));

                    $current_readings['weather_symbol'] = (trim($dmo_reading['sy']) == '')? '0' : $this->dayOrNightSymbol($dmo_reading['sy'], $dmo_reading['utc'], array("sunrise"=>$sunrise,"sunset"=>$sunset));

                    // Replace the null values with hypen character and round it off to the nearest tenths
                    $current_readings['temperature'] = ($dmo_reading['tl'] == '')? '0' : number_format($dmo_reading['tl'],0);
                    $current_readings['precipitation'] = ($dmo_reading['rr'] == '')? '0' : round($dmo_reading['rr'],0);
                    $current_readings['relative_humidity'] = ($dmo_reading['rh'] == '')? '0' : round($dmo_reading['rh'],0);
                    $current_readings['wind_speed'] = ($dmo_reading['ff'] == '')? '0' : floor($dmo_reading['ff'] * 1.852 + 0.5);
                    $current_readings['wind_gust'] = ($dmo_reading['g3h'] == '')? '0' : round($dmo_reading['g3h'],0);
                    $current_readings['wind_direction'] = $this->windDirection($dmo_reading['dir']);


                    $theirTime = strtotime($dmo_reading['Datum'] . $dmo_reading['utc'] . ':' .$dmo_reading['min']);
                    //$current_time = strtotime(date('Ymd H:i:s')) + $Date->getOffset();
                    $current_readings['local_time'] = date('Ymd H:i:s', $theirTime + $Date->getOffset());
                    $current_readings['update'] = date('h:iA', $theirTime + $Date->getOffset());

                    //if(strtotime($dmo_reading['local_time']) > $current_time ) 
                    $today_Readings[] = $current_readings;

                }

            }

            $current_reading = array_pop($today_Readings);
            
        }else{
            $this->log('File not found - ' . $dmo_readings_file);
            $current_reading = array();
        }
        
//        $this->log(print_r($current_reading, TRUE));
        
        // Check if the last/current reading exist, and set status
        if(count($current_reading)>0){
            $abfrageResults['reading'] = $current_reading;
            $abfrageResults['reading']['status'] = 'ok';
            $abfrageResults['reading']['sunrise'] = $sunrise;
            $abfrageResults['reading']['sunset'] = $sunset;
            
        }else{
            $abfrageResults['reading']['status'] = 'none';
        }
        
        $nearestGP = $this->nearestGridPoint($stationInfo['lon'],$stationInfo['lat']);
        
        $dmo_forecast_dir = Configure::read('Data.dmo');
        $dmo_forecast_file = $dmo_forecast_dir . $nearestGP['lon'] . '_' . $nearestGP['lat'] . '.csv';
        
        if(file_exists($dmo_forecast_file)){
            
            $this->log('File found - ' . $dmo_forecast_file);
            
            $csvString = file_get_contents($dmo_forecast_file);
            $forecasts = $this->csvToArray($csvString);
        
            // Get sunrise and sunset using current latituted and longtitude station
            $sunrise = $this->sunInfo($stationInfo['lat'], $stationInfo['lon'], 'sunrise');
            $sunset = $this->sunInfo($stationInfo['lat'], $stationInfo['lon'], 'sunset');

            foreach($forecasts as $forecast){

                $new_forecast = array();

                if(trim($forecast['tl'])!=''){

                    $new_forecast['Datum'] = $forecast['Datum'];
                    $new_forecast['utc'] = $forecast['utc'];
                    $new_forecast['min'] = $forecast['min'];

                    $new_forecast['weather_condition'] = (trim($forecast['sy']) == '')? '0' : $this->dayOrNightSymbol($forecast['sy'], $forecast['utc'], array("sunrise"=>$sunrise,"sunset"=>$sunset));

                    $new_forecast['precipitation'] = ($forecast['rain3'] == '')? '0' : number_format($forecast['rain3'],1);
                    $new_forecast['relative_humidity'] = ($forecast['rh'] == '')? '0' : round($forecast['rh'],0);
                    $new_forecast['wind_speed'] = ($forecast['ff'] == '')? '0' : floor($forecast['ff'] * 1.852 + 0.5);
                    $new_forecast['wind_gust'] = ($forecast['g6h'] == '')? '0' : floor($forecast['g6h'] * 1.852 + 0.5);
                    $new_forecast['temperature'] = ($forecast['tl'] == '')? '0' : number_format($forecast['tl'],0); 

                    // Translate raw date to 3 hourly range value
                    $thierTime = strtotime($forecast['Datum'].' '.$forecast['utc'].':'.$forecast['min']);

                    $new_forecast['their_time'] = date('Y-m-d H:i:s', $thierTime);

                    $new_forecast['localtime'] = date('Ymd H:i:s', $thierTime + $Date->getOffset());

                    $new_forecast['localtime_range_start'] = date('Ymd H:i:s', strtotime('-3 hours', $thierTime) + $Date->getOffset()); 
                    $new_forecast['localtime_range_end'] = date('Ymd H:i:s', $thierTime + $Date->getOffset());

                    $new_forecast['localtime_range'] = date('hA', strtotime($new_forecast['localtime_range_start'])) . '-' . date('hA', strtotime($new_forecast['localtime_range_end']));

                    // Generate the wind description
                    $new_forecast['wind_description'] = $this->showWindDescription($forecast['dir'], $new_forecast['wind_speed'], $new_forecast['wind_gust']);

                    // Translate raw data to wind direction image value
                    $new_forecast['wind_direction'] = $this->showWindDirection($forecast['dir']);
                    
                    $readingTime = date('Ymd H:i:s', strtotime(date('Ymd H:i:s')) + $Date->getOffset());
                    
                    if (strtotime($new_forecast['localtime']) > strtotime($readingTime)) $abfrageResults['forecast'][$new_forecast['Datum']][] = $new_forecast;

                    //$abfrageResults['forecast'][$new_forecast['Datum']][] = $new_forecast;

                }
            }

            //$abfrageResults['forecast'] = $this->localTimeForecast($abfrageResults['forecast']);
            $abfrageResults['forecast_dmo_file_csv'] = $nearestGP['lon'] . '_' . $nearestGP['lat'] . '.csv';
            
        }else{
            $this->log('File not found - ' . $dmo_forecast_file);
        }
        
        if(key_exists('forecast', $abfrageResults) AND count($abfrageResults['forecast'])>0){
           $abfrageResults['forecast_status'] = 'ok'; 
        } else {
           $abfrageResults['forecast_status'] = 'none'; 
        }
             
        $abfrageResults['stationId'] = $stationId;
        $abfrageResults['stationName'] = $stationInfo['name'];
        
        $this->log(print_r($abfrageResults, TRUE));
        
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
        $location_id = $fields['conditions']['id']; 
        $locationInfo = $NimaName->find('all', array('fields' => array('id' ,'lat', 'long', 'full_name_ro'),  'conditions' => array( 'id =' => $location_id)));
        $locationInfo = $locationInfo[0]['Name'];
        
        $nearestGP = $this->nearestGridPoint($locationInfo['long'],$locationInfo['lat']);
        
        $station_temp = $this->findStationByGP($nearestGP['lon'], $nearestGP['lat']);
        
        // Get sunrise and sunset using current latituted and longtitude station
        $sunrise = $this->sunInfo($locationInfo['lat'], $locationInfo['long'], 'sunrise');
        $sunset = $this->sunInfo($locationInfo['lat'], $locationInfo['long'], 'sunset');
        
        // Readings
        $dmo_readings_dir = Configure::read('Data.readings');
        $dmo_readings_file = $dmo_readings_dir . date('Ydm') . '.csv';
        
        if(file_exists($dmo_readings_file)){ 
            $this->log('File found - ' . $dmo_readings_file);
            $csvString = file_get_contents($dmo_readings_file);
        }else{
            $this->log('File not found - ' . $dmo_readings_file);
            //exit;
        }
        
        $dmo_readings = array();
        $dmo_readings = $this->csvToArray($csvString);
        $dmo_readings = $this->cleanDmoReadings($dmo_readings);
        
        //$this->log(print_r($dmo_readings, TRUE));
        
        $today_Readings = $current_readings = array();
        
        if(isset($station_temp[0]['station_id'])) {
        
            foreach($dmo_readings as $dmo_reading){

                if(in_array($station_temp[0]['station_id'], $dmo_reading)){


                    $current_readings['moonphase'] = $this->moon_phase(date('Y', strtotime($dmo_reading['Datum'])), date('m', strtotime($dmo_reading['Datum'])), date('d', strtotime($dmo_reading['Datum'])));

                    $current_readings['weather_symbol'] = (trim($dmo_reading['sy']) == '')? '0' : $this->dayOrNightSymbol($dmo_reading['sy'], $dmo_reading['utc'], array("sunrise"=>$sunrise,"sunset"=>$sunset));
    //                           
    //                // Replace the null values with hypen character and round it off to the nearest tenths
                    $current_readings['temperature'] = ($dmo_reading['tl'] == '')? '0' : number_format($dmo_reading['tl'],0);
                    $current_readings['precipitation'] = ($dmo_reading['rr'] == '')? '0' : round($dmo_reading['rr'],0);
                    $current_readings['relative_humidity'] = ($dmo_reading['rh'] == '')? '0' : round($dmo_reading['rh'],0);
                    $current_readings['wind_speed'] = ($dmo_reading['ff'] == '')? '0' : floor($dmo_reading['ff'] * 1.852 + 0.5);
                    $current_readings['wind_gust'] = ($dmo_reading['g3h'] == '')? '0' : round($dmo_reading['g3h'],0);
                    $current_readings['wind_direction'] = $this->windDirection($dmo_reading['dir']);
    //                
    //                
                    $theirTime = strtotime($dmo_reading['Datum'] . $dmo_reading['utc'] . ':' .$dmo_reading['min']);
    //                //$current_time = strtotime(date('Ymd H:i:s')) + $Date->getOffset();
                    $current_readings['local_time'] = date('Ymd H:i:s', $theirTime + $Date->getOffset());
                    $current_readings['update'] = date('h:iA', $theirTime + $Date->getOffset());
    //                
    //                //if(strtotime($dmo_reading['local_time']) > $current_time ) 
                    $today_Readings[] = $current_readings;
    //                
                }

            }
            
            $current_reading = array_pop($today_Readings);
            
            $dmoResults['stationId'] = $station_temp[0]['station_id'];
        
        }else{
            
            $current_reading = array();
            
        }
        
        // Check if the last/current reading exist, and set status
        if(count($current_reading)>0){
            $dmoResults['reading'] = $current_reading;
            $dmoResults['reading']['status'] = 'ok';
            $dmoResults['reading']['sunrise'] = $sunrise;
            $dmoResults['reading']['sunset'] = $sunset;
        }else{
            $dmoResults['reading']['status'] = 'none';
        }
        
        //$this->log(print_r($current_reading, TRUE));
        
        $dmo_forecast_dir = Configure::read('Data.dmo');
        $dmo_forecast_file = $dmo_forecast_dir . $nearestGP['lon'] . '_' . $nearestGP['lat'] . '.csv';
        
        if(file_exists($dmo_forecast_file)){
            
            $this->log('File found - ' . $dmo_forecast_file);
            
            $csvString = file_get_contents($dmo_forecast_file);
            $forecasts = $this->csvToArray($csvString);

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

                        $new_forecast['their_time'] = date('Y-m-d H:i:s', $thierTime);

                        $new_forecast['localtime'] = date('Ymd H:i:s', $thierTime + $Date->getOffset());

                        $new_forecast['localtime_range_start'] = date('Ymd H:i:s', strtotime('-3 hours', $thierTime) + $Date->getOffset()); 
                        $new_forecast['localtime_range_end'] = date('Ymd H:i:s', $thierTime + $Date->getOffset());

                        $new_forecast['localtime_range'] = date('hA', strtotime($new_forecast['localtime_range_start'])) . '-' . date('hA', strtotime($new_forecast['localtime_range_end']));

                        // Generate the wind description
                        $new_forecast['wind_description'] = $this->showWindDescription($forecast['dir'], $new_forecast['wind_speed'], $new_forecast['wind_gust']);

                        // Translate raw data to wind direction image value
                        $new_forecast['wind_direction'] = $this->showWindDirection($forecast['dir']);
                        
                        $readingTime = date('Ymd H:i:s', strtotime(date('Ymd H:i:s')) + $Date->getOffset());
                    
                        if (strtotime($new_forecast['localtime']) > strtotime($readingTime)) $dmoResults['forecast'][$new_forecast['Datum']][] = $new_forecast;

                        //$dmoResults['forecast'][$new_forecast['Datum']][] = $new_forecast;

                }
            }

            //$dmoResults['forecast'] = $this->localTimeForecast($dmoResults['forecast']);

            $dmoResults['forecast_dmo_file_csv'] = $nearestGP['lon'] . '_' . $nearestGP['lat'] . '.csv';
            
        }else{
            $this->log('File not found - ' . $dmo_forecast_file);
            exit;
        }
        
        if(key_exists('forecast', $dmoResults) AND count($dmoResults['forecast'])>0){
           $dmoResults['forecast_status'] = 'ok'; 
        } else {
           $dmoResults['forecast_status'] = 'none'; 
        }
             
        $dmoResults['location_id'] = $location_id;
        $dmoResults['stationName'] = $locationInfo['full_name_ro'];
        
        //$this->log(print_r($dmoResults, TRUE));
        
        return $dmoResults;
        
    }
    
    
    
}