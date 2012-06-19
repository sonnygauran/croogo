<?php

/**
 * @author Jaggy Gauran
 * 
 * Meteomedia Plugin - Abfrage
 *  
 */
class Abfrage{
    
    /**
     * Usage: 
     * 
     * 1. Import class:
     * App::import('Lib', 'Abfrage');
     * 
     * 2. Initialize an instance
     * $Abfrage = new Abfrage($stationId);
     * 
     * 3. Class methods
     * $Abfrage->generateURL();
     * 
     */
    
    private $stationId;
    private $stationIds;
    
    /**
     * set the station ID on instance creation
     * 
     * @param type $stationId 
     */
    public function __construct($stationId) {
        if (is_array($stationId) AND !empty($stationId)) {
            $this->stationIds = $stationId;
        } else {
            $this->stationId = $stationId;
        }
        
        CakeLog::write('abfrage', 'Station ID: ' . $this->stationId);
        
    }

    /**
     * Translate all parameters into raw weather abbreviations
     * 
     * @param type $parameters
     * @return string 
     */
    private function translateWeatherCodes($parameters){
        /**
         * Used: tl,tx,tn,td,rh,ff,g1h,dir,qff,sh,gl1h, g3h, rain6,rr,sy,sy2 
         */
        $raw = array();
        $legend = array(
            'Temperature' => array(
                'low' => 'tl',
                'maximum' => 'tx',
                'max' => 'tx',
                'minimum' => 'tn',
                'min' => 'tn',
                'dew point' => 'td',
            ),
            'Rainfall' => array(
                'Period' => 'rr',
                '1 hour' => 'rr1h',
                '3 hours' => 'rain3',
                '6 hours' => 'rain6',
            ),
            'Wind' => array(
                'speed' => 'ff',
                'direction' => 'dir',
            ),
            'Gust' => array(
                '1 hour' => 'g1h',
                '3 hours' => 'g3h',
            ),
            'Weather Symbols' => array(
                'Set 1' => 'sy',
                'Set 2' => 'sy2'
            ),
            'Global Radiation' => array(
                '1 hour' => 'gl1h',
            ),
            'Humidity' => 'rh',
            'Air Pressure' => 'qff',
            'Sunshine' => 'sh'
        );
        
        /**
         * Loop through the parameters given 
         */
        foreach($parameters as $key => $value){
            if(is_array($value)){
                foreach($value as $end){
                    // detect the corresponding code for the parameter
                    $raw[count($raw)] = $legend[$key][$end];
                }
            }else{
                // detect the corresponding code for the parameter
                $raw[count($raw)] = $legend[$value];
            }
        }
        
        return $raw;
    }
    
    /**
     * Translate the keys to German terminology
     * 
     * @param type $details
     * @return type 
     */
    private function translateKeys($details){
        $legend = array(
            'time_resolution' => 'zeiten1',
            'start_date'      => 'datumstart',
            'end_date'        => 'datumend',
            'start_hour'      => 'utcstart',
            'end_hour'        => 'utcend',
            'linked'          => 'verknuepft',
            'call'            => 'aufruf',
        );
        
        foreach($details as $key => $value){
            if(!empty($legend[$key])){
                $details[$legend[$key]] = $value;
                unset($details[$key]);
            }
        }
        return $details;
    }

    /**
     * Dynamically Generate URLs using given parameters 
     */
    public function generateURL($format, $parameters){
       $counter = 0;
       
       /**
        * Parameter Types
        * 
        */
       $url['parameter_type'] = ($format['time_resolution'] == '10m') ? 'reading' : 'forecast';
       
       $url['stationId'] = '';
       if (!empty($this->stationIds)) {
           $url['stationId'] = implode(',', $this->stationIds);
       } else {
           $url['stationId'] = $this->stationId;
       }
       
       /**
        * Default Parameters
        * 
        * output=csv2
        * ortoutput=wmo6,name
        * timefill=nein
        * verknuepft=nein
        * aufruf=auto
        *  
        */
        $url['defaults'] = array(
            'output' => 'csv2', // comma separated values
            'ortoutput' => 'wmo6,name',
            'call' => 'auto', // aufruf 
        );
        
        /**
         * Unset when we're getting reading values
         *  
         */
        if($url['parameter_type'] == 'reading'){
            unset($url['defaults']['linked']);
            unset($url['defaults']['timefill']);
        }
        
        $url['defaults'] = $this->translateKeys($url['defaults']);        
        
        /**
         * Format
         * 
         * Time resolution
         * Known as: zeiten1
         * 
         * Input used: 10m (Readings), 3h (Forecast)
         * 
         * Usage: 
         *  [format]  => Array(
         *      [time_resolution] => '' //zeiten1
         *      [start_date]      => '' //datumstart
         *      [end_date]        => '' //datumend
         *      [start_hour]      => '' //utcstart
         *      [end_hour]        => '' //utcend
         * )
         * 
         */
        $url['format'] = $this->translateKeys($format);
        
        /**
         * Parameter Lists
         * Known as: parameterliste
         * 
         * Inputs used: 
         * get()
         *  Reading:    rr,rh,sy,sy2
         *  Forecast:   rr,rh,sy,sy2 
         * 
         * getWeeklyForecast()
         *  Reading:    rr,rh,sy,sy2
         *  Forecast:   rr,rh,sy,sy2 
         * 
         * getDetailedForecast()
         *  Forecast:    tl,tx,tn,td,rh,ff,g1h,dir,qff,sh,gl1h
         * 
         * getStationInfo()
         *  Forecast:   
         * 
         * Should detect specifics :
         *  tl,dir,ff,g3h,rain6
         * 
         * Sample:
         * 
         * [parameters] => Array 
         *   ( 
         *       [0] => rr 
         *       [1] => rh 
         *       [2] => sy 
         *       [3] => sy2 
         *   )
         */
        $url['parameters'] = $this->translateWeatherCodes($parameters);
        
        $url['generated'] = "http://192.168.20.89/abfrage.php?stationidstring={$url['stationId']}&";
        
        // Append the date format
        foreach($url['format'] as $key => $value){
            if($value == '') continue; // Skip if the given parameter has no value
            $url['generated'] .= "$key=$value&";
        }
        
        /**
         * Spacial cases for parameters... specifically for wind
         *  
         */
            if($url['parameter_type'] == 'forecast'){
                $url['generated'] .= "paramtyp=mos_mix_mm&&";
            }

            
        $url['generated'] .= "paramliste=";
        
        // Append the parameter lists
        foreach($url['parameters'] as $parameter){
            $url['generated'] .= "{$parameter}";
            // Hope therre's a better way to do this
            $counter++;
            $url['generated'] .= ($counter == count($url['parameters'])) ? '&' : ',';
        }
        
        // Append the default parameters
        foreach($url['defaults'] as $key=>$value){
            $url['generated'] .= "$key=$value&";
        }
        
        /**
         * Add a special parameter if wind speed is being traced 
         */
        if(in_array('ff', $url['parameters']) && in_array('g1h', $url['parameters']) && count($url['parameters']) == 2)  $url['generated'] .= 'unit=2';
        
        /**
         * logs the array in WEBROOT/tmp/logs/abfrage.log
         *  
         */
        CakeLog::write('abfrage', print_r($url, true));
        return $url['generated'];
    }
}
