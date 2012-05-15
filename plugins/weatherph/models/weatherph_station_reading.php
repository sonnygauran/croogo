<?php

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
        
        $this->log($startutc . '-' . $endutc);
        
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
        
//        debug($curlResults);exit;
        
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
                $readings['sy'] = $this->dayOrNightSymbol(number_format($readings['sy'],0), $readings['utc'], array("sunrise"=>$sunrise,"sunset"=>$sunset));
                
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
        
        //$this->log($resultsForecast);
        
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
                $result['sy'] = $this->dayOrNightSymbol(number_format($result['sy'],0), $result['utc'], array("sunrise"=>$sunrise,"sunset"=>$sunset));
                
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
                
                //$this->log(print_r($abfrageResults, TRUE));
                
            }
        }
        
        return $abfrageResults;
        
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
    /*public function find($conditions = null, $fields = array(), $order = null, $recursive = null)
    {
        $date = 'YYYY-MM-DD';
        
        //debug($fields);exit;
        
        $readingCount = 0;
        $id = $fields['conditions']['id'];
        
        if (key_exists('date', $fields['conditions'])) {
            $date = $fields['conditions']['date'];
        } else {
            $date = date('Ymd', strtotime('-2 day -8 hours'));
        }
        
        if (key_exists('datumend', $fields['conditions'])) {
            $datumend = $fields['conditions']['datumend'];
        } else {
            $datumend = date('Ymd');
        }
        
        
        $time = date('H', strtotime('-8 hours'));
        //echo $time = date('H:i:s', strtotime('- hours'));
            //exit;
        include dirname(__FILE__) . '/auth.php';
        
        
        
        //$url = 'http://abfrage.meteomedia.ch/abfrage.php?stationidstring='.$id.'&datumstart='.$date.'&datumend='.$datumend.'&tl=on&td=on&tx=on&tn=on&t5=on&dir=on&ff=on&g1h=on&g3h=on&qff=on&qnh=on&qfe=on&ap=on&www=on&vis=on&n=on&l=on&metarwx=on&cov=on&clcmch=on&clg=on&rr10m=on&rr1h=on&rain3=on&rain6=on&rain12=on&sno=on&new=on&s10=on&sh=on&ss24=on&gl10=on&gl1h=on&gl24=on&stationsreihe=on&output=csv2&ortoutput=wmo6,name&aufruf=auto';
        //$url = 'http://abfrage.meteomedia.ch/manila.php?stationidstring='.$id.'&datumstart='.$date.'&datumend='.$datumend.'&tl=on&td=on&tx=on&tn=on&dir=on&ff=on&g1h=on&g3h=on&qff=on&qnh=on&qfe=on&ap=on&www=on&vis=on&n=on&l=on&metarwx=on&cov=on&clcmch=on&clg=on&rr10m=on&rr1h=on&rain3=on&rain6=on&rain12=on&sno=on&new=on&s10=on&sh=on&ss24=on&gl10=on&gl1h=on&gl24=on&stationsreihe=on&output=csv2&ortoutput=wmo6,name&aufruf=auto';
        
        $url = 'http://abfrage.meteomedia.ch/manila.php?stationidstring='.$id.'&datumstart='.$date.'&datumend='.$datumend.'&dir=on&tl=on&ff=on&td=on&tx=on&tn=on&qff=on&ap=on&www=on&metarwx=on&vis=on&cov=on&n=on&l=on&clcmch=on&clg=on&output=csv2&ortoutput=wmo6,name&aufruf=auto';
        
        $this->log($url);
        
        $stations = array();
        $location = $url;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $location);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, "{$karten['username']}:{$karten['password']}");
        curl_setopt($ch, CURLOPT_USERAGENT, "Weather.com.ph Curl Client 1.0");
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); //times out after 10s 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $result = curl_exec($ch);
        
        //debug($result);exit;
        
        //$headersSpecimen = 'Datum;utc;min;ort1;dir;ff;ff;g3h;g1h;g1h;tl;tl;td;td;t5;t5;tx;tx;tn;tn;qfe;qff;qff;qnh;ap;www;www;metarwx;vis;cov;n;l;clcmch;clcmch;clg;rr10m;rr1h;rr1h;rr24h;sno;sno;new;new;ss24;ss24;sh;sh;s10;gl24;gl24;gl1h;gl1h;gl10;rain3;rain6;rain12;';
        
        $headersSpecimen = 'Datum;utc;min;ort1;dir;ff;tl;td;tx;tn;qff;ap;www;metarwx;vis;cov;n;l;clcmch;clg;';
        
        $expected = strstr($result, $headersSpecimen);
        
        if ($expected == '') {
            // The expected string should contain the headerSpecimen
            $error = 'There was an error generating the CSV from '.$url;
            $this->log($error);
            throw new Exception('There was an error generating the CSV');
            return array();
        }
        
        $rows = explode("\n", $result);
        //$numrow=count($rows);
        //$this->log(print_r($rows, true));
        $headers = explode(';', $rows[0]);
        //print_r($headers);

        unset($rows[0]);

        $station_map = array();
        foreach ($rows as $row) {
            if (strlen(trim($row)) > 0) {
                $row = explode(';', $row);
                //print_r($row);
                //$station_map[$rows]['dir'];

                $index = array();
                $current = array();
                foreach ($row as $key => $field) {
                    $index = $headers[$key];
                    if (strlen($index) == 0)
                    {
                        // IGNORE empty indexes
                    }
                    else
                    {
                        $current[$index] = $field;
                    }
                }

                $cleanData = true;

                if ($cleanData) {
                    $station_map[] = $current;
                }
            } // Check for empty rows
        }// Loop through each line

        $readings = array();
        if (is_string($conditions) AND $conditions == 'all' AND empty($fields))
        {
            // default behavior.
            $readings = $station_map;
        }
        else if (is_string($conditions) AND $conditions == 'first')
        {
            $readings = reset($station_map);
        }
        else if (is_string($conditions) AND is_array($fields) AND key_exists('conditions', $fields)) 
        {
            
            $readings = $station_map;
        //$readings = $station_map;
        }
        
        //debug($readings);exit;
        
        $reversedReadings = $readings;
        rsort($reversedReadings);
        //$this->log($reversedReadings);
        
        
        $validRecord = array();
        foreach ($reversedReadings as $reading) {
            //$this->log(print_r($reading, true));
            if (strlen(trim($reading['dir'])) > 0) {
                if(key_exists('utc', $fields['conditions'])){
                    $validRecord[] = $reading;
                }  else {
                    $validRecord = $reading;
                    break;
                }
                
                
            }
        }
        
        //debug($validRecord);
 
        
        //$this->log(print_r($validRecord, true));
        if (!empty($validRecord)) 
        {
            $this->log('memememe');
            return $validRecord;
//            foreach ($readings as $currentReading)
//            {
//                //if ($currentReading['utc'] == $time) {
//                    return $currentReading;
//            //}
//            }
        } else {
            return array();
        }
        
        $this->log('meme');
        
//        die(print_r($readings, true));
//        foreach ($readings as $row) {
//                $rdate = $row['Datum'];
//                $rtime = $row['utc'];
//                
//                if ($rdate == $date AND $rtime == $time)
//                {
//                
//                $stations[] = array(
//                    'Datum' => $rdate,
//                    'utc' => $rtime
//                );
//                }
//                
//                //if $stations[][]
//        }
        curl_close($ch);

        return $reading;
    }*/
    
    
    
    /*public function get($conditions = null, $fields = array(), $order = null, $recursive = null){
        
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
        
        $this->log($startutc . '-' . $endutc);
        
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
        
//        debug($curlResults);exit;
        
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
                $readings['sy'] = $this->dayOrNightSymbol(number_format($readings['sy'],0), $readings['utc'], array("sunrise"=>$sunrise,"sunset"=>$sunset));
                
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
        
        //$this->log($resultsForecast);
        
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
                $result['sy'] = $this->dayOrNightSymbol(number_format($result['sy'],0), $result['utc'], array("sunrise"=>$sunrise,"sunset"=>$sunset));
                
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
                
                //$this->log(print_r($abfrageResults, TRUE));
                
            }
        }
        
        return $abfrageResults;
        
    }*/
    
    /*public function get($conditions = null, $fields = array(), $order = null, $recursive = null)
    {
        error_reporting(0);
        
        App::import('Model', 'Weatherph.WeatherphStation');
        
        $WeatherphStation = new WeatherphStation();
        $stations = $WeatherphStation->find('all', array('conditions' => array(
            'provider' => 'pagasa',
        )));
       
        //debug($stations);exit;

        $date = 'YYYY-MM-DD';
        
        include dirname(__FILE__) . '/auth.php';
        
        ini_set('memory_limit', '256M');
        
        $WeatherphStationReading = new WeatherphStationReading();
        
        $stationsReadings = array();
        
        foreach($stations as $station){
            
            $datumstart = $datumend = date('Ymd',strtotime($fields['conditions']['date']));
            
            $currentReadings = $WeatherphStationReading->find('all', array('conditions' => array(
                    'id' => $station['id'],
                    'date' => $datumstart,
                    'datumend' => $datumend,
                    'utc' => 'all',
            )));
            
            foreach($currentReadings as $currentReading){
                   
                $stationsReadings
                    [$currentReading['ort1']]
                        [$currentReading['Datum']]
                            [$currentReading['utc']]= $currentReading;

            }
            
            
            //debug($stationsReadings);exit;
            
        }
        
        //debug($stationsReadings);exit;
        
        return $stationsReadings;

        
    }*/
    
    
    
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
    
    public function getDetailedReading($conditions = null, $fields = array(), $order = null, $recursive = null){
        
        include dirname(__FILE__) . '/auth.php';
        
        //$this->log(print_r($fields['conditions']));
        
        $stationId = $fields['conditions']['id'];
        $type = $fields['conditions']['type'];
        $timeRes = ($fields['conditions']['timeRes'] == NULL )? '1h' : $fields['conditions']['timeRes'];
        
        $startdatum = $fields['conditions']['startDatum'];
        // Input Validation
        $startdatum = ($startdatum == NULL)? date('Ymd') : date('Ymd',strtotime($startdatum));
        
        
        
        //$this->log($fields);
        $enddatum = $fields['conditions']['endDatum'];
        
        //$this->log($enddatum);
        
        // Input validation
        $enddatum = ($enddatum == NULL)? date('Ymd') : date('Ymd', strtotime($enddatum)); 
        
        
        //Grab stations readings  
        //$url = "http://192.168.20.89/abfrage.php?stationidstring=$stationId&datumstart=$startdatum&datumend=$enddatum&zeiten1=$timeRes&mosmess=ja&paramliste=tl,td,dir,ff,g1h,qff,rr,rh,sd1,gl1&output=csv2&ortoutput=wmo6,name&aufruf=auto";
        
        $url="http://192.168.20.89/abfrage.php?stationidstring=$stationId&datumstart=$startdatum&datumend=$enddatum&zeiten1=$timeRes&mosmess=ja&rain6=on&tn=on&paramliste=tl,tx,td,rh,ff,g1h,dir,qff,sh,rr,gl1h&output=csv2&ortoutput=wmo6,name&aufruf=auto";
        //$url="http://172.17.2.34/snowflake/api.php?query[measure]=dir,ff,g3h,tl,td,qff,rr,sh,gl1h,rh&station[id]=$stationId&time[from]=$startdatum&time[to]=$enddatum&zeiten1=$timeRes&time[suffix]=00:00&output[format]=CSV";

        
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
        
        //$this->log(print_r($curlResults));
        
        $headersSpecimen = 'Datum;utc;min;ort1;dir;ff;g1h;tl;td;tx;tn;qff;rr;sh;gl1h;rain6;rh;';
        //$headersSpecimen = 'Datum;utc;min;ort1;tl;td;rh;ff;g1h;dir;qff;sh;gl1;rr';
        
        $results = $this->csvToArray($curlResults, $headersSpecimen);
        //Jett
        $this->log($results);
        
        foreach($results as $result){
            
            if(trim($result['tl'])!=''){
         
                //if(strtotime($result['Datum']) >= strtotime(date('Ymd'))){
                //explode the ort1 raw data, grab only those needed
                $result['ort1'] = explode('/', $result['ort1']);
                unset($result['ort1'][0]);
                $result['ort1'] = implode('/', $result['ort1']);
                
                $abfrageResults['ort1'] = $result['ort1']; 
                $abfrageResults['readings'][$result['Datum']][] = $result;
                //}    
            }
       
        }
        
        //$this->log($abfrageResults);
        
        //$this->log($type);
        
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
    
    //$this->log(print_r($csv));
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
    
    
    
    /*public function getStationInfo($stationID = NULL, $keys = NULL){
        
        if($stationID == NULL){
            
            $error = 'Station ID is required'.$url;
            $this->log($error);
            throw new Exception('Station ID is required');
            return NULL;
            
        }else{
             
            include dirname(__FILE__) . '/auth.php';

            $url = "http://192.168.20.89/abfrage.php?stationidstring=$stationID&ortsinfo=ja&paramtyp=mos_mix_mm&output=html&aufruf=auto";

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
        
    }*/
    
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
    
    // Used to get the maximum and minimum in a set of array.
    private function amaxmin($array, $val, $arg){
        
        foreach($array as $key=>$arr){
            
            foreach($arr as $ar){
                if(!isset($arrmaxmin[$key])) {
                    if(trim($ar[$val])!='') {
                       $arrmaxmin[$key] = array(
//                           'utcDate' => strtotime($ar['Datum'] . ' ' . $ar['utc'] . ':' . $ar['min']),
//                           'data' => $ar[$val],
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
                    if($arg == 'max' && $ar[$val]>$arrmaxmin[$key]['y']){
                        $arrmaxmin[$key] = array(
                            'x' => strtotime($ar['Datum'] . ' ' . $ar['utc'] . ':' . $ar['min']),
                            'y' => trim($ar[$val]),
                            'data' => $ar[$val],
                        );
                        
                    }elseif($arg == 'min' && $ar[$val]<$arrmaxmin[$key]['y']){
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
    
}

