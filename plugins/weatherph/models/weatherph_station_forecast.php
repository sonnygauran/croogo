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
        
        date_default_timezone_set('UTC');
        
        
        $daysStr = ($fields['conditions']['target_days'] > 1)? 'days' : 'day';
        $fields['conditions']['target_days'] = $fields['conditions']['target_days'] - 1;
        
        $stationId = $fields['conditions']['id'];
 
        $startdatum = date('Ymd');
        $enddatum = strtotime("+" . $fields['conditions']['target_days'] . $daysStr, strtotime($startdatum));
        $enddatum = date('Ymd', $enddatum);
        $utch = $fields['conditions']['utch'];
        
        $startutc = "00";
        $endutc = "00";
        
        $url = "http://192.168.20.89/abfrage.php?stationidstring=$stationId&datumstart=$startdatum&datumend=$enddatum&&zeiten1=$utch&paramtyp=mos_mix_mm&mosmess=ja&tl=on&dir=on&ff=on&g3h=on&paramliste=rr,rh,sy,sy2&output=csv2&ortoutput=wmo6,name&aufruf=auto";
        
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
        
        $stationInfo = $this->getStationInfo($stationId, array("lat","lon"));
        
        //debug($stationInfo);exit;
        //$stationLong = $this->getStationInfo($stationId, "lon");
        
             
        $headersSpecimen = "Datum;utc;min;ort1;dir;ff;g3h;tl;rr;sy;rh;sy2;";
        
        $results = $this->csvToArray($curlResults, $headersSpecimen);
        
        //debug($results);exit;
        
        $abfrageResults = array();
        foreach($results as $result){
            
            if(trim($result['tl'])!=''){
                
                $sunrise = date_sunrise(strtotime($result['Datum']), SUNFUNCS_RET_STRING, $stationInfo['lat'], $stationInfo['lon'], 90);
                $sunset = date_sunset(strtotime($result['Datum']), SUNFUNCS_RET_STRING, $stationInfo['lat'], $stationInfo['lon'], 90);

                $abfrageResults['sunrise'] = $sunrise;
                $abfrageResults['sunset'] = $sunset;

                $result['ort1'] = explode('/', $result['ort1']);
                unset($result['ort1'][0]);
                $result['ort1'] = implode('/', $result['ort1']);
                
                $abfrageResults['ort1'] = $result['ort1'];
                
                // Remove decimal of the raw data for symbol
                $result['sy'] = $this->dayOrNightSymbol(number_format($result['sy'],0), $result['utc']);
                
                // Replace the null values with hypen character and round it off to the nearest tenths
                $result['tl'] = ($result['tl'] == '')? '-' : round($result['tl'],0);
                $result['rr'] = ($result['rr'] == '')? '-' : round($result['rr'],0);
                $result['rh'] = ($result['rh'] == '')? '-' : round($result['rh'],0);
                $result['ff'] = ($result['ff'] == '')? '-' : round($result['ff'],0);
                $result['g3h'] = ($result['g3h'] == '')? '-' : round($result['g3h'],0);
                
                // Translate raw date to 3 hourly range value
                $result['utch'] = $result['utc'] . ':' . $result['min'] .'<br />'. sprintf('%02d',$result['utc'] + 3) .':'. $result['min'];
                
                // Translate raw data to wind direction image value
                $result['dir'] = $this->showWindDirection($result['dir']);
                
                unset($result['Datum'],$result['ort1']);
                
                $abfrageResults['utc'.$result['utc']] = $result;
                
                
            }
            
            
        }
        
        //debug($abfrageResults);exit;
       
        return $abfrageResults;
        
    }
    
    public function csvToArray($csv, $headersSpecimen){
        
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
        
    }
    
    private function dayOrNightSymbol($symbol = NULL, $utc = NULL){
        
        if($symbol == NULL || trim($symbol) == ''){
//            $error = 'Weather Symbol Code is required'.$url;
//            $this->log($error);
//            throw new Exception('Weather Symbol Code is required');
//            return NULL;
            
              return NULL;
              
        }else{
            
            $utc = (int)$utc;
            
            //debug($utc);
            
            if($utc <= 12 && $utc >= 0 ){
                return 'day_' . $symbol;
            }elseif($utc <= 24 && $utc > 12){
                return 'night_' . $symbol;
            }else{
                $error = 'UTC is required'.$url;
                $this->log($error);
                throw new Exception('UTC is required');
                return NULL;
            }
            
        }
        
    }
    
    private function showWindDirection($wd = NULL) {
        
        if($wd == NULL || trim($wd) == ''){
            
//            $error = 'Wind direction raw data is required'.$url;
//            $this->log($error);
//            throw new Exception('Wind direction raw data is required');
//            return NULL;
            
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

            return $value;
        }
    }
}