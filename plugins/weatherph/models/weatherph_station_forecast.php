<?php

App::import('Lib', 'Meteomedia.Abfrage');
App::import('Lib', 'Meteomedia.Curl');

/**
 * Acquires the of Weather Forecast Stations
 */
class WeatherphStationForecast extends WeatherphAppModel {

    public $name = 'WeatherphStationForecast';
    public $useTable = false;
    
    public function get($conditions = null, $fields = array(), $order = null, $recursive = null){
        
        $station_id = $fields['conditions']['id'];

        // Get the default timestamp timezone
        $siteTimezone = Configure::read('Site.timezone');
        $Date = new DateTime(null, new DateTimeZone($siteTimezone));

        // Get station info based on id
        $station_info = $this->getStationInfo($station_id);

        // Get sunrise and sunset using current latituted and longtitude station
        $sunrise = $this->sunInfo($station_info['lat'], $station_info['lon'], 'sunrise');
        $sunset = $this->sunInfo($station_info['lat'], $station_info['lon'], 'sunset');

        //FROM DATABASE
        App::import('Model', 'Weatherph.Reading');
        $reading_temp = new Reading();
        
        $sql_condition = array( 
                'ort1 LIKE' => "%".$station_id ."%", 
                'tl !=' => '');
        
        if($station_info['org'] == 'JRG'){
            $sql_condition['min ='] = '00';
        }

        date_default_timezone_set('UTC');
        $date = date('Y-m-d', strtotime('-2 hours'));
        $hour = date('H', strtotime('-2 hours'));
        
        
        $station_readings = $reading_temp->find('all', array(
            'conditions' => array(
                'ort1 LIKE'     => "{$station_id}%",
                'datum >='      => $date,
                'utc >='         => $hour,
                'min'           => '00'
            ),
            'order' => 'datum DESC, utc DESC, min DESC',
            'limit' => '1'
       ));
        
	   
        
        
        $current_readings = array();

        
        if (count($station_readings) > 0) {
            
            $current_readings = array(
                'weather_symbol' => '-',
                'temperature' => '-',
                'precipitation' => '-',
                'precipitation_hr_range' => '',
                'relative_humidity' => '-',
                'wind_direction' => '-',
                'wind_description' => '-',
                'wind_speed_direction' => '-',
                'wind_speed' => '-',
                'wind_gust' => '-',
                'dew_point' => '-',
    //            '' => '-',
            );

            $current_reading = $station_readings[0]['Reading'];
            
            $this->log(print_r($current_reading, TRUE));
            
            if(key_exists('sy', $current_reading) && trim($current_reading['sy']) != ''){
                $current_readings['weather_symbol'] = $this->dayOrNightSymbol($current_reading['sy'], $current_reading['utc'], array("sunrise"=>$sunrise,"sunset"=>$sunset));
            }
            
            if(key_exists('tl', $current_reading) && trim($current_reading['tl']) != ''){
                $current_readings['temperature'] = number_format($current_reading['tl'],0) . "&deg;C";    
            }
            
            if(key_exists('rr1h', $current_reading) && key_exists('rain6', $current_reading)){
                if(trim($current_reading['rr1h']) != ''){
                    $current_readings['precipitation'] = ($current_reading['rr1h'] < 0)? "0.0mm" : number_format($current_reading['rr1h'], 1) . "mm";    
                    $current_readings['precipitation_hr_range'] = "(1H)";
                }else{
                    if(trim($current_reading['rain6']) != ''){
                        $current_readings['precipitation'] = ($current_reading['rain6'] < 0)? "0.0mm" : number_format($current_reading['rain6'], 1) . "mm";  
                        $current_readings['precipitation_hr_range'] = "(6H)"; 
                    }
                }
            }
            
            if(key_exists('rh', $current_reading) && trim($current_reading['rh']) != ''){
                $current_readings['relative_humidity'] = round($current_reading['rh'],0) . "%";
            }
            
            if(key_exists('ff', $current_reading) && trim($current_reading['ff']) != ''){
                $current_readings['wind_speed'] = floor($current_reading['ff'] * 1.852 + 0.5) . "km/h";
            }
            
            if(key_exists('g1h', $current_reading) && trim($current_reading['g1h']) != ''){
                $current_readings['wind_gust'] = round($current_reading['g1h'],0) . "km/h";
            }

            $theirTime = strtotime($current_reading['datum'] . $current_reading['utc'] . ':' .$current_reading['min']);
            $current_readings['local_time'] = date('Ymd H:i:s', $theirTime + $Date->getOffset());
            $current_readings['update'] = date('h:iA', $theirTime + $Date->getOffset());
        }

        if (count($current_readings) > 0) {
            $abfrageResults['reading'] = $current_readings;
            $abfrageResults['reading']['status'] = 'ok';
        } else {
            $abfrageResults['reading']['status'] = 'none';
        }
        
        $nearestGP = $this->nearestGridPoint($station_info['lon'],$station_info['lat']);
        
        $dmo_forecast_dir = Configure::read('Data.dmo');
        $dmo_forecast_file = $dmo_forecast_dir . $nearestGP['lon'] . '_' . $nearestGP['lat'] . '.csv';

        if (file_exists($dmo_forecast_file)) {

            $this->log('File found - ' . $dmo_forecast_file);

            $csvString = file_get_contents($dmo_forecast_file);
            $resultsForecasts = $this->csvToArray($csvString);

            foreach ($resultsForecasts as $forecast) {

                $current_forecast = array(
                    'Datum' => $forecast['Datum'],
                    'utc' => $forecast['utc'],
                    'min' => $forecast['min'],
                    'weather_symbol' => '-',
                    'precipitation' => '-',
                    'precipitation_severity' => '-',
                    'relative_humidity' => '-',
                    'wind_speed' => '-',
                    'wind_gust' => '-',
                    'temperature' => '-',
                    'wind_description' => '-',
//                    '' => '-',
                );

                if(key_exists('sy', $forecast) && trim($forecast['sy']) != ''){
                    $current_forecast['weather_symbol'] = $this->dayOrNightSymbol($forecast['sy'], $forecast['utc'], array("sunrise"=>$sunrise,"sunset"=>$sunset));
                }

                if(key_exists('rain3', $forecast) && trim($forecast['rain3']) != ''){
                    $current_forecast['precipitation'] = ($forecast['rain3'] < 0.5)? "0mm" : round($forecast['rain3']) . "mm";
                    if($forecast['rain3'] < 0.1){
                        $current_forecast['precipitation_severity'] = "No Rain";
                    }else if($forecast['rain3'] < 0.5){
                        $current_forecast['precipitation_severity'] = "Chance of Shower";
                        $current_forecast['precipitation'] = '';
                    }else if ($forecast['rain3'] >= 0.5 && $forecast['rain3'] < 1.5) {
                        $current_forecast['precipitation_severity'] = "Slight Rain";                            
                    }
                    else if ($forecast['rain3'] >= 1.5 && $forecast['rain3'] < 12) {
                        $current_forecast['precipitation_severity'] = "Moderate Rain";
                    }else if ($forecast['rain3'] >= 12) {
                        $current_forecast['precipitation_severity'] = "Severe Rain";
                    }
                }

                if(key_exists('rh', $forecast) && trim($forecast['rh']) != ''){
                    $current_forecast['relative_humidity'] = round($forecast['rh'],0) . "%";
                }

                if(key_exists('ff', $forecast) && trim($forecast['ff']) != ''){
                    $current_forecast['wind_speed'] = floor($forecast['ff'] * 1.852 + 0.5) . "km/h";
                }

                if(key_exists('tl', $forecast) && trim($forecast['tl']) != ''){
                    $current_forecast['temperature'] = number_format($forecast['tl'],0) . "&deg;C"; 
                }

                $thierTime = strtotime($forecast['Datum'].' '.$forecast['utc'].':'.$forecast['min']);
                $current_forecast['their_time'] = date('Ymd H:i:s', $thierTime);
                $current_forecast['localtime'] = date('Ymd H:i:s', $thierTime + $Date->getOffset());

                $current_forecast['localtime_range_start'] = date('Ymd H:i:s', strtotime('-3 hours', $thierTime) + $Date->getOffset()); 
                $current_forecast['localtime_range_end'] = date('Ymd H:i:s', $thierTime + $Date->getOffset());
                $current_forecast['localtime_range'] = date('hA', strtotime($current_forecast['localtime_range_start'])) . '-' . date('hA', strtotime($current_forecast['localtime_range_end']));

                $readingTime = date('Ymd H:i:s', strtotime(date('Ymd H:i:s')) + $Date->getOffset());

                if(strtotime($current_forecast['localtime']) > strtotime($readingTime)) $abfrageResults['forecast'][] = $current_forecast;

            }

            $abfrageResults['forecast_dmo_file_csv'] = $nearestGP['lon'] . '_' . $nearestGP['lat'] . '.csv';
            
        }else {
            $this->log('File not found - ' . $dmo_forecast_file);
        }

        if (key_exists('forecast', $abfrageResults) && count($abfrageResults['forecast']) > 0) {
            $abfrageResults['forecast']['status'] = 'ok';
        } else {
            $abfrageResults['forecast']['status'] = 'none';
        }
        
        $abfrageResults['station_name'] = $station_info['name'];
        
        return $abfrageResults;
    }

    private function forecastsDateOffsets() {

        $date = strtotime('-' . Configure::read('Site.time_overlap') . ' hours');
        $start = date('Ymd H:i:s', $date);

        $date = strtotime('+' . Configure::read('Site.offset'));
        $end = date('Ymd H:i:s', strtotime('+' . Configure::read('Site.forecast_range') . ' Days', $date));

        return array('startDate' => $start, 'endDate' => $end);
    }

    private function localTimeForecast($datasets = NULL) {

        // Get the default timestamp timezone
        $siteTimezone = Configure::read('Site.timezone');
        $Date = new DateTime(null, new DateTimeZone($siteTimezone));

//        $this->log('Localtime' . print_r($datasets, TRUE));

        $new_datasets = array();

        $today = date('Ymd H:i:s', strtotime(date('Ymd H:i:s')) + $Date->getOffset());

        foreach ($datasets as $key => $dataset) {

            foreach ($dataset as $index => $data) {

                $new_key = date('Ymd', strtotime($data['localtime']));

                if (strtotime($data['localtime_range_end']) >= strtotime($today)) {

                    $new_datasets[$new_key][] = $data;
                }
            }
        }

        $newer_datasets = array();
        $dummy_datasets = $new_datasets;

        $skip = true;

        foreach ($new_datasets as $index_date => $datasets) {

            $next_date = date('Ymd', strtotime('+1 day', strtotime($index_date)));

            if (key_exists($next_date, $dummy_datasets)) {

                $array_sets = $dummy_datasets[$next_date][0]; //

                array_push($datasets, $array_sets);

                if ($skip == false)
                    unset($datasets[0]);

                $skip = false;

                $newer_datasets[$index_date] = $datasets;
            }
        }

        return $newer_datasets;
    }
    
    public function getWeeklyForecast($conditions = null, $fields = array(), $order = null, $recursive = null){
        
        $station_id = $fields['conditions']['id'];

        // Get the default timestamp timezone
        $siteTimezone = Configure::read('Site.timezone');
        $Date = new DateTime(null, new DateTimeZone($siteTimezone));

        // Get station info based on id
        $station_info = $this->getStationInfo($station_id);

        // Get sunrise and sunset using current latituted and longtitude station
        $sunrise = $this->sunInfo($station_info['lat'], $station_info['lon'], 'sunrise');
        $sunset = $this->sunInfo($station_info['lat'], $station_info['lon'], 'sunset');

        //FROM DATABASE
        App::import('Model', 'Weatherph.Reading');
        $reading_temp = new Reading();
        
        $sql_condition = array( 
                'ort1 LIKE' => "%".$station_id ."%", 
                'tl !=' => '');
        
        if($station_info['org'] == 'JRG'){
            $sql_condition['min ='] = '00';
        }

        date_default_timezone_set('UTC');
        $date = date('Y-m-d', strtotime('-2 hours'));
        $hour = date('H', strtotime('-2 hours'));
        
        
        $station_readings = $reading_temp->find('all', array(
            'conditions' => array(
                'ort1 LIKE'     => "{$station_id}%",
                'datum >='      => $date,
                'utc >='         => $hour,
                'min'           => '00'
            ),
            'order' => 'datum DESC, utc DESC, min DESC',
            'limit' => '1'
       ));     
                
        $current_readings = array();
        
        if (count($station_readings) > 0) {
            
            $current_readings = array(
                'weather_symbol' => '-',
                'temperature' => '-',
                'precipitation' => '-',
                'precipitation_hr_range' => '-',
                'relative_humidity' => '-',
                'wind_direction' => '-',
                'wind_description' => '-',
                'wind_speed_direction' => '-',
                'wind_speed' => '-',
                'wind_gust' => '-',
                'dew_point' => '-',
    //            '' => '-',
            );

            $current_reading = $station_readings[0]['Reading'];
            
            if(key_exists('sy', $current_reading) && trim($current_reading['sy']) != ''){
                $current_readings['weather_symbol'] = $this->dayOrNightSymbol($current_reading['sy'], $current_reading['utc'], array("sunrise"=>$sunrise,"sunset"=>$sunset));
            }
            
            if(key_exists('tl', $current_reading) && trim($current_reading['tl']) != ''){
                $current_readings['temperature'] = number_format($current_reading['tl'],1). "&deg;C";
            }
            
            if(key_exists('td', $current_reading) && trim($current_reading['td']) != ''){
                $current_readings['dew_point'] = number_format($current_reading['td'],0). "&deg;C";
            }
            
            if(key_exists('rr1h', $current_reading) && key_exists('rain6', $current_reading)){
                if(trim($current_reading['rr1h']) != ''){
                    $current_readings['precipitation'] = ($current_reading['rr1h'] <= 0)? "0 mm" : number_format($current_reading['rr1h'], 1) . " mm";    
                    $current_readings['precipitation_hr_range'] = "(1H)";
                }else{
                    if(trim($current_reading['rain6']) != ''){
                        $current_readings['precipitation'] = ($current_reading['rain6'] <= 0)? "0 mm" : number_format($current_reading['rain6'], 1) . " mm"; 
                        $current_readings['precipitation_hr_range'] = "(6H)"; 
                    }
                }
            }
            
            if(key_exists('rh', $current_reading) && trim($current_reading['rh']) != ''){
                $current_readings['relative_humidity'] = round($current_reading['rh'],0) . "%";
            }
            
            if(key_exists('ff', $current_reading) && trim($current_reading['ff']) != ''){
                $current_readings['wind_speed'] = floor($current_reading['ff'] * 1.852 + 0.5) . " km/h";
            }
            
            if(key_exists('g1h', $current_reading) && trim($current_reading['g1h']) != ''){
                $current_readings['wind_gust'] = floor($current_reading['g1h'] * 1.852 + 0.5) . " km/h";
            }

            if(key_exists('dir', $current_reading)){
                $current_readings['wind_direction'] = (trim($current_reading['dir']) == '')? '-' : $this->showWindDirection($current_reading['dir']);
                $current_readings['wind_description'] = (trim($current_reading['dir']) == '')? '-' : $this->WindDirection($current_reading['dir']);
                $current_readings['wind_speed_direction'] = (trim($current_reading['dir']) == '')? '-' : $current_readings['wind_speed'] . ', ' . $current_readings['wind_description']['eng'];
            }
            
            $theirTime = strtotime($current_reading['datum'] . $current_reading['utc'] . ':' .$current_reading['min']);
            $current_readings['local_time'] = date('Ymd H:i:s', $theirTime + $Date->getOffset());
            $current_readings['update'] = date('h:iA', $theirTime + $Date->getOffset());
        }

        if (count($current_readings) > 0) {
            $abfrageResults['reading'] = $current_readings;
            $abfrageResults['reading']['status'] = 'ok';
        } else {
            $abfrageResults['reading']['status'] = 'none';
        }
        
        $abfrageResults['sunrise'] = $sunrise;
        $abfrageResults['sunset'] = $sunset;
        $abfrageResults['moonphase'] = $this->moon_phase(date('Y'), date('m'), date('d'));
        
        $nearestGP = $this->nearestGridPoint($station_info['lon'],$station_info['lat']);
        
        $dmo_forecast_dir = Configure::read('Data.dmo');
        $dmo_forecast_file = $dmo_forecast_dir . $nearestGP['lon'] . '_' . $nearestGP['lat'] . '.csv';

        if (file_exists($dmo_forecast_file)) {

            $this->log('File found - ' . $dmo_forecast_file);

            $csvString = file_get_contents($dmo_forecast_file);
            $forecasts = $this->csvToArray($csvString);
            
            foreach ($forecasts as $forecast) {

                if (trim($forecast['tl']) != '') {
                    
                    $current_forecast = array(
                        'Datum' => $forecast['Datum'],
                        'utc' => $forecast['utc'],
                        'min' => $forecast['min'],
                        'weather_condition' => '-',
                        'precipitation' => '-',
                        'precipitation_severity' => '-',
                        'precipitation_hr_range' => '-',
                        'relative_humidity' => '-',
                        'wind_speed' => '-',
                        'wind_gust' => '-',
                        'temperature' => '-',
                        'dew_point' => '-',
                        'wind_description' => '-',
                        'wind_direction' => '-',                        
                    );
                    
                    if(key_exists('sy', $forecast) && trim($forecast['sy']) != ''){
                        $current_forecast['weather_condition'] = $this->dayOrNightSymbol($forecast['sy'], $forecast['utc'], array("sunrise"=>$sunrise,"sunset"=>$sunset));
                    }
                    
                    if(key_exists('rain3', $forecast) && trim($forecast['rain3']) != ''){
                        $current_forecast['precipitation'] = ($forecast['rain3'] < 0.5)? "0mm" : round($forecast['rain3']) . "mm";
                        $current_forecast['precipitation_hr_range'] = '3h';
                        
                        if($forecast['rain3'] < 0.1){
                            $current_forecast['precipitation_severity'] = "No Rain";
                        }else if($forecast['rain3'] < 0.5){
                            $current_forecast['precipitation_severity'] = "Chance of Shower";
                            $current_forecast['precipitation'] = '';
                        }else if ($forecast['rain3'] >= 0.5 && $forecast['rain3'] < 1.5) {
                            $current_forecast['precipitation_severity'] = "Slight Rain";                            
                        }
                        else if ($forecast['rain3'] >= 1.5 && $forecast['rain3'] < 12) {
                            $current_forecast['precipitation_severity'] = "Moderate Rain";
                        }else if ($forecast['rain3'] >= 12) {
                            $current_forecast['precipitation_severity'] = "Severe Rain";
                        }
                    }
                    
                    if(key_exists('rain6', $forecast) && trim($forecast['rain6']) != ''){
                        $current_forecast['precipitation'] = ($forecast['rain6'] < 0.5)? "0mm" : round($forecast['rain6']) . "mm";
                        $current_forecast['precipitation_hr_range'] = '6h';
                        
                        
                        if($forecast['rain6'] < 0.1){
                            $current_forecast['precipitation_severity'] = "No Rain";
                        }else if($forecast['rain6'] < 0.5){
                            $current_forecast['precipitation_severity'] = "Chance of Shower";
                            $current_forecast['precipitation'] = '';
                        }else if ($forecast['rain6'] >= 0.5 && $forecast['rain6'] < 3) {
                            $current_forecast['precipitation_severity'] = "Slight Rain";                            
                        }
                        else if ($forecast['rain6'] >= 3 && $forecast['rain6'] < 24) {
                            $current_forecast['precipitation_severity'] = "Moderate Rain";
                        }else if ($forecast['rain6'] >= 24) {
                            $current_forecast['precipitation_severity'] = "Severe Rain";
                        }
                    }
                    
                    if(key_exists('rh', $forecast) && trim($forecast['rh']) != ''){
                        $current_forecast['relative_humidity'] = round($forecast['rh'],0) . "%";
                    }
                    
                    if(key_exists('ff', $forecast) && trim($forecast['ff']) != ''){
                        $current_forecast['wind_speed'] = floor($forecast['ff'] * 1.852 + 0.5) . " km/h";
                    }
                    
                    if(key_exists('g6h', $forecast) && trim($forecast['g6h']) != ''){
                        $current_forecast['wind_gust'] = floor($forecast['g6h'] * 1.852 + 0.5) . " km/h";
                    }
                    
                    if(key_exists('tl', $forecast) && trim($forecast['tl']) != ''){
                        $current_forecast['temperature'] = number_format($forecast['tl'],0) . "&deg;C"; 
                    }
                    
                    if(key_exists('td', $forecast) && trim($forecast['td']) != ''){
                        $current_forecast['dew_point'] = number_format($forecast['td'],0) . "&deg;C"; 
                    }
                    
                    if(key_exists('dir', $forecast)){
                        $current_forecast['wind_description'] = $this->showWindDescription($forecast['dir'], $current_forecast['wind_speed'], $current_forecast['wind_gust']);
                        $current_forecast['wind_direction'] = $this->showWindDirection($forecast['dir']);
                    }

                    $thierTime = strtotime($forecast['Datum'].' '.$forecast['utc'].':'.$forecast['min']);
                    $current_forecast['their_time'] = date('Y-m-d H:i:s', $thierTime);
                    $current_forecast['localtime'] = date('Ymd H:i:s', $thierTime + $Date->getOffset());
                    $current_forecast['localtime_range_start'] = date('Ymd H:i:s', strtotime('-3 hours', $thierTime) + $Date->getOffset()); 
                    $current_forecast['localtime_range_end'] = date('Ymd H:i:s', $thierTime + $Date->getOffset());
                    if($current_forecast['precipitation_hr_range'] === '6h') $current_forecast['localtime_range_start'] = date('Ymd H:i:s', strtotime('-3 hours', strtotime($current_forecast['localtime_range_start'])));
                    $current_forecast['localtime_range'] = date('hA', strtotime($current_forecast['localtime_range_start'])) . '-' . date('hA', strtotime($current_forecast['localtime_range_end']));

                    $readingTime = date('Ymd H:i:s', strtotime(date('Ymd H:i:s')) + $Date->getOffset());

                    if (strtotime($current_forecast['localtime']) > strtotime($readingTime))
                        $abfrageResults['forecast'][$current_forecast['Datum']][] = $current_forecast;
                }
            }

            $abfrageResults['forecast_dmo_file_csv'] = $nearestGP['lon'] . '_' . $nearestGP['lat'] . '.csv';
        }else {
            $this->log('File not found - ' . $dmo_forecast_file);
        }

        if (key_exists('forecast', $abfrageResults) AND count($abfrageResults['forecast']) > 0) {
            $abfrageResults['forecast_status'] = 'ok';
        } else {
            $abfrageResults['forecast_status'] = 'none';
        }

        $abfrageResults['station_id'] = $station_id;
        $abfrageResults['station_name'] = $station_info['name'];
        $abfrageResults['altitude'] = $station_info['alt'];
                
//        $this->log(print_r($abfrageResults, TRUE));
        
        return $abfrageResults;
    }

    public function getDetailedForecast($conditions = null, $fields = array(), $order = null, $recursive = null) {

        // Get the default timestamp timezone
        $siteTimezone = Configure::read('Site.timezone');
        $Date = new DateTime(null, new DateTimeZone($siteTimezone));

        $stationId = $fields['conditions']['id'];
        $Abfrage = new Abfrage($stationId);
        $parameters = array();

        $type = $fields['conditions']['type'];
        $timeRes = ($fields['conditions']['timeRes'] == NULL ) ? '1h' : $fields['conditions']['timeRes'];

        switch ($type) {
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
                    'Wind' => array(
                        'direction'
                    )
                );
                //$data_time_resolution = '3h';
                break;
        }

        //$timeRes = $data_time_resolution;

        $url = $Abfrage->generateURL($this->generateDate('chart', $timeRes), $parameters);


        $gum = $stationId . '_detailed_forecast_' . sha1(end(explode('?', $url)));
        $curlResults = NULL;
        if (!Cache::read($gum, '3hour')) {
            $curlResults = Curl::getData($url);
            Cache::write($gum, $curlResults, '3hour');
        } else {
            $curlResults = Cache::read($gum, '3hour');
        }

        $results = $this->csvToArray($curlResults);

        foreach ($results as $result) {

            //explode the ort1 raw data, grab only those needed
            $result['ort1'] = explode('/', $result['ort1']);
            unset($result['ort1'][0]);
            $result['ort1'] = implode('/', $result['ort1']);

            $abfrageResults['ort1'] = $result['ort1'];

            $utcDate = strtotime($result['Datum'] . $result['utc'] . ':' . $result['min']) + $Date->getOffset();

            $result['Datum'] = date('Ymd', $utcDate);
            $result['utc'] = date('H', $utcDate);
            $result['min'] = date('m', $utcDate);

            $abfrageResults['forecast'][$result['Datum']][] = $result;
        }

        $resultData = array();
        switch ($type) {
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
                    'tl' => array('name' => 'tlseries', 'style' => 'tlline', 'use_hand_cursor' => 'False', 'hoverable' => 'False'),
                    'td' => array('name' => 'tdseries', 'style' => 'tdline', 'use_hand_cursor' => 'False', 'hoverable' => 'False'),
                    'tx' => array('name' => 'txseries', 'style' => 'noline', 'use_hand_cursor' => 'True', 'hoverable' => 'False'),
                    'tn' => array('name' => 'tnseries', 'style' => 'noline', 'use_hand_cursor' => 'True', 'hoverable' => 'False'),
                );
                $resultData['additional'] = array(
                    'tl' => array('tooltip' => array('enabled' => 'True')),
                    'td' => array('tooltip' => array('enabled' => 'True')),
                    'tx' => array('marker' => array('enabled' => 'true', 'style' => 'dotred')),
                    'tn' => array('marker' => array('enabled' => 'true', 'style' => 'dotblue')),
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
                    'ff' => array('name' => 'ffseries', 'style' => 'ffline', 'use_hand_cursor' => 'False', 'hoverable' => 'False'),
                    'g1h' => array('name' => 'g1hseries', 'style' => 'g1hline', 'use_hand_cursor' => 'False', 'hoverable' => 'False'),
                );
                $resultData['additional'] = array(
                    'ff' => array('tooltip' => array('enabled' => 'false')),
                    'g1h' => array('tooltip' => array('enabled' => 'false')),
                );
                break;
            case 'winddir':

                $windDir = $this->popValArray($abfrageResults['forecast'], 'dir', NULL, '0.5');

                foreach ($windDir as $dir) {
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
                    'dir' => array('name' => 'dirline', 'style' => 'dirline', 'use_hand_cursor' => 'False', 'hoverable' => 'False'),
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
                    'rh' => array('name' => 'rhseries', 'style' => 'rhline', 'use_hand_cursor' => 'False', 'hoverable' => 'False'),
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
                    'rain6' => array('name' => 'rain6series', 'style' => '', 'use_hand_cursor' => 'False', 'hoverable' => 'True'),
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
                    'qff' => array('name' => 'qffseries', 'style' => '', 'use_hand_cursor' => 'False', 'hoverable' => 'True'),
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
                    'sh' => array('name' => 'shseries', 'style' => 'sunshine', 'use_hand_cursor' => 'False', 'hoverable' => 'False'),
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
                    'gl1h' => array('name' => 'gl1hseries', 'style' => 'globalradiation', 'use_hand_cursor' => 'False', 'hoverable' => 'True'),
                );
                $resultData['additional'] = array(
                    'gl1h' => array('tooltip' => array('enabled' => 'true')),
                );
                break;
        }

        $abfrageResults = ($type != NULL) ? $resultData : $abfrageResults;

        return $abfrageResults;
    }

    private function highTemp($array) {
        foreach ($array as $arr) {
            if (date('H', $arr['x']) == '20') {
                $result[] = array(
                    'x' => $arr['x'],
                    'y' => $arr['data'],
                    'data' => $arr['data'],
                );
            }
        }
        return $result;
    }

    private function LowTemp($array) {
        foreach ($array as $arr) {
            if (date('H', $arr['x']) == '08') {
                $result[] = array(
                    'x' => $arr['x'],
                    'y' => $arr['data'],
                    'data' => $arr['data'],
                );
            }
        }
        return $result;
    }

        public function dmoForecast($condition = NULL, $fields = array()) {

        // Get the default timestamp timezone
        $siteTimezone = Configure::read('Site.timezone');
        $Date = new DateTime(null, new DateTimeZone($siteTimezone));

        $station_id = $fields['conditions']['id'];
        $search = $fields['conditions']['coordinates'];

        $station_info = $this->getStationInfo($station_id);

        // Get sunrise and sunset using current latituted and longtitude station
        $sunrise = $this->sunInfo($station_info['lat'], $station_info['lon'], 'sunrise');
        $sunset = $this->sunInfo($station_info['lat'], $station_info['lon'], 'sunset');
        
        $nearestGP = $this->nearestGridPoint($search['lon'], $search['lat']);
        $e = new Exception();

        //FROM DATABASE
        App::import('Model', 'Weatherph.Reading');
        $reading_temp = new Reading();
        
        $sql_condition = array(
                'ort1 LIKE' => "%" . $station_id . "%",
                'tl !=' => '');
        
        if($station_info['org'] == 'JRG'){
            $sql_condition['min ='] = '00';
        }

        $station_readings = $reading_temp->find('all', array(
            'conditions' => $sql_condition,
            'order' => 'datum DESC, utc DESC, min DESC',
            'limit' => 1,
                ));
        
        $current_readings = array();

        if (count($station_readings) > 0) {
            
            $current_readings = array(
                'weather_symbol' => '-',
                'temperature' => '-',
                'precipitation' => '-',
                'precipitation_severity' => '-',
                'precipitation_hr_range' => '-',
                'relative_humidity' => '-',
                'wind_direction' => '-',
                'wind_description' => '-',
                'wind_speed_direction' => '-',
                'wind_speed' => '-',
                'wind_gust' => '-',
                'dew_point' => '-',
    //            '' => '-',
            );

            $current_reading = $station_readings[0]['Reading'];

            $this->log("Current Readings:" . print_r($current_reading, TRUE));
            
            if(key_exists('sy', $current_reading) && trim($current_reading['sy']) != ''){
                $current_readings['weather_symbol'] = $this->dayOrNightSymbol($current_reading['sy'], $current_reading['utc'], array("sunrise" => $sunrise, "sunset" => $sunset));
            }

            if(key_exists('tl', $current_reading) && trim($current_reading['tl']) != ''){
                $current_readings['temperature'] = number_format($current_reading['tl'], 1) . "&deg;C";
            }
            
            if(key_exists('rr1h', $current_reading) && key_exists('rain6', $current_reading)){
                if(trim($current_reading['rr1h']) != ''){
                    $current_readings['precipitation'] = ($current_reading['rr1h'] < 0.1)? "0.0mm" : number_format($current_reading['rr1h'], 1) . "mm";    
                    $current_readings['precipitation_hr_range'] = "(1H)";
                }else{
                    if(trim($current_reading['rain6']) != ''){
                        $current_readings['precipitation'] = ($current_reading['rain6'] < 0.1)? "0.0mm" : number_forecast($current_reading['rain6'], 1) . "mm";
                        $current_readings['precipitation_hr_range'] = "(6H)"; 
                    }
                }
            }
            
            if(key_exists('rh', $current_reading) && trim($current_reading['rh']) != ''){
                $current_readings['relative_humidity'] = round($current_reading['rh'], 0) . "%";
            }
            
            if(key_exists('ff', $current_reading) && trim($current_reading['ff']) != ''){
                $current_readings['wind_speed'] = floor($current_reading['ff'] * 1.852 + 0.5) . "km/h";
            }
            
            if(key_exists('g1h', $current_reading) && trim($current_reading['g1h']) != ''){
                $current_readings['wind_gust'] = floor($current_reading['g1h'] * 1.852 + 0.5) . "km/h";
            }
            
            if(key_exists('dir', $current_reading)){
                $current_readings['wind_direction'] = $this->showWindDirection($current_reading['dir']);
                $current_readings['wind_description'] = $this->WindDirection($current_reading['dir']);
                $current_readings['wind_speed_direction'] = (trim($current_reading['dir']) == '')? '-' : $current_readings['wind_speed'] . ', ' . $current_readings['wind_description']['eng'];
            }
            
            $theirTime = strtotime($current_reading['datum'] . $current_reading['utc'] . ':' . $current_reading['min']);
            $current_readings['local_time'] = date('Ymd H:i:s', $theirTime + $Date->getOffset());
            $current_readings['update'] = date('h:iA', $theirTime + $Date->getOffset());
        }

        $dmoResults['station_id'] = $station_id;
        
        // Check if the last/current reading exist, and set status
        if (count($current_readings) > 0) {
            $dmoResults['reading'] = $current_readings;
            $dmoResults['reading']['status'] = 'ok';
        } else {
            $dmoResults['reading']['status'] = 'none';
        }

        $dmoResults['sunrise'] = $sunrise;
        $dmoResults['sunset'] = $sunset;
        $dmoResults['moonphase'] = $this->moon_phase(date('Y'), date('m'), date('d'));

        $dmo_forecast_dir = Configure::read('Data.dmo');
        $dmo_forecast_file = $dmo_forecast_dir . $nearestGP['lon'] . '_' . $nearestGP['lat'] . '.csv';

        if (file_exists($dmo_forecast_file)) {

            $this->log('File found - ' . $dmo_forecast_file);

            $csvString = file_get_contents($dmo_forecast_file);
            $forecasts = $this->csvToArray($csvString);

            foreach ($forecasts as $forecast) {

                if (trim($forecast['tl']) != '') {
                    
                    $current_forecast = array(
                        'Datum' => $forecast['Datum'],
                        'utc' => $forecast['utc'],
                        'min' => $forecast['min'],
                        'weather_symbol' => '-',
                        'precipitation' => '-',
                        'precipitation_hr_range' => '-',
                        'relative_humidity' => '-',
                        'wind_speed' => '-',
                        'wind_gust' => '-',
                        'temperature' => '-',
                        'dew_point' => '-',
                        'wind_description' => '-',
                        'wind_direction' => '-',
                    );
                    
                    if(key_exists('sy', $forecast) && trim($forecast['sy']) != ''){
                        $current_forecast['weather_symbol'] = $this->dayOrNightSymbol($forecast['sy'], $forecast['utc'], array("sunrise" => $sunrise, "sunset" => $sunset));
                    }
                    
                    if(key_exists('rain3', $forecast) && trim($forecast['rain3']) != ''){
                        $current_forecast['precipitation'] = ($forecast['rain3'] < 0.5)? "0mm" : round($forecast['rain3']) . "mm";
                        $current_forecast['precipitation_hr_range'] = "3h";
                        
                        if($forecast['rain3'] < 0.1){
                            $current_forecast['precipitation_severity'] = "No Rain";
                        }else if($forecast['rain3'] < 0.5){
                            $current_forecast['precipitation_severity'] = "Chance of Shower";
                            $current_forecast['precipitation'] = '';
                        }else if ($forecast['rain3'] >= 0.5 && $forecast['rain3'] < 1.5) {
                            $current_forecast['precipitation_severity'] = "Slight Rain";                            
                        }
                        else if ($forecast['rain3'] >= 1.5 && $forecast['rain3'] < 12) {
                            $current_forecast['precipitation_severity'] = "Moderate Rain";
                        }else if ($forecast['rain3'] >= 12) {
                            $current_forecast['precipitation_severity'] = "Severe Rain";
                        }
                    }
                    if(key_exists('rain6', $forecast) && trim($forecast['rain6']) != ''){
                        $current_forecast['precipitation'] = ($forecast['rain6'] < 0.5)? "0mm" : round($forecast['rain6']) . "mm";
                        $current_forecast['precipitation_hr_range'] = "6h";
                        
                        if($forecast['rain6'] < 0.1){
                            $current_forecast['precipitation_severity'] = "No Rain";
                        }else if($forecast['rain6'] < 0.5){
                            $current_forecast['precipitation_severity'] = "Chance of Shower";
                            $current_forecast['precipitation'] = '';
                        }else if ($forecast['rain6'] >= 0.5 && $forecast['rain6'] < 3) {
                            $current_forecast['precipitation_severity'] = "Slight Rain";                            
                        }
                        else if ($forecast['rain6'] >= 3 && $forecast['rain6'] < 24) {
                            $current_forecast['precipitation_severity'] = "Moderate Rain";
                        }else if ($forecast['rain6'] >= 24) {
                            $current_forecast['precipitation_severity'] = "Severe Rain";
                        }
                    }
                    
                    if(key_exists('rh', $forecast) && trim($forecast['rh']) != ''){
                        $current_forecast['relative_humidity'] = round($forecast['rh'], 0) . "%";
                    }
                    
                    if(key_exists('ff', $forecast) && trim($forecast['ff']) != ''){
                        $current_forecast['wind_speed'] = floor($forecast['ff'] * 1.852 + 0.5) . "km/h";
                    }
                    
                    if(key_exists('g6h', $forecast) && trim($forecast['g6h']) != ''){
                        $current_forecast['wind_gust'] = floor($forecast['g6h'] * 1.852 + 0.5) . "km/h";
                    }
                    
                    if(key_exists('tl', $forecast) && trim($forecast['tl']) != ''){
                        $current_forecast['temperature'] = number_format($forecast['tl'], 0) . "&deg;C";
                    }
                    
                    if(key_exists('td', $forecast) && trim($forecast['td']) != ''){
                        $current_forecast['dew_point'] = number_format($forecast['td'], 0) . "&deg;C";
                    }
                    
                    if(key_exists('dir', $forecast)){
                        $current_forecast['wind_description'] = $this->showWindDescription($forecast['dir'], $current_forecast['wind_speed'], $current_forecast['wind_gust']);
                        $current_forecast['wind_direction'] = $this->showWindDirection($forecast['dir']);
                    }

                    $thierTime = strtotime($forecast['Datum'] . ' ' . $forecast['utc'] . ':' . $forecast['min']);
                    $current_forecast['their_time'] = date('Y-m-d H:i:s', $thierTime);
                    $current_forecast['localtime'] = date('Ymd H:i:s', $thierTime + $Date->getOffset());

                    $current_forecast['localtime_range_start'] = date('Ymd H:i:s', strtotime('-3 hours', $thierTime) + $Date->getOffset());
                    $current_forecast['localtime_range_end'] = date('Ymd H:i:s', $thierTime + $Date->getOffset());
                    if($current_forecast['precipitation_hr_range'] === '6h') $current_forecast['localtime_range_start'] = date('Ymd H:i:s', strtotime('-3 hours', strtotime($current_forecast['localtime_range_start'])));
                    $current_forecast['localtime_range'] = date('hA', strtotime($current_forecast['localtime_range_start'])) . '-' . date('hA', strtotime($current_forecast['localtime_range_end']));

                    $readingTime = date('Ymd H:i:s', strtotime(date('Ymd H:i:s')) + $Date->getOffset());

                    if (strtotime($current_forecast['localtime']) > strtotime($readingTime))
                        $dmoResults['forecast'][$current_forecast['Datum']][] = $current_forecast;
                }
            }

            $dmoResults['forecast_dmo_file_csv'] = $nearestGP['lon'] . '_' . $nearestGP['lat'] . '.csv';

        }else {
            $this->log('File not found - ' . $dmo_forecast_file);
        }

        if (key_exists('forecast', $dmoResults) AND count($dmoResults['forecast']) > 0) {
            $dmoResults['forecast_status'] = 'ok';
        } else {
            $dmoResults['forecast_status'] = 'none';
        }

        $dmoResults['station_name'] = $station_info['name'];

        return $dmoResults;
    }
    

}
