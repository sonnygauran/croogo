<?php

/**
 * @author Jaggy Gauran
 * 
 * Meteomedia Plugin - Abfrage
 *  
 */
class Abfrage{

    /**
     * Translate all parameters into raw weather abbreviations
     * 
     * @param type $parameters
     * @return string 
     */
    public function translateWeatherCodes($parameters){
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
                '1 hour' => 'rr',
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
        
        
        CakeLog::write('parameters', print_r($parameters, true));
        
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
    public function translateKeys($details){
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
    public function generateURL($stationId, $type, $format, $parameters, $extras = ''){
       $counter = 0;
        
       $url['stationId'] = $stationId;
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
            'timefill' => 'nein',
            'linked' => 'nein', // verknuepft
            'output' => 'csv2', // comma separated values
            'ortoutput' => 'wmo6,name',
            'call' => 'auto', // aufruf 
        );
        
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
         * Parameter Types
         * Known as: paramtyp
         * 
         * Input used: paramtyp=mos_mix_mm
         * 
         */
        $url['parameter_type'] = $type;

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
        $url['special_parameters'] = $this->translateWeatherCodes($extras);
        
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
        if($url['parameter_type'] == 'wind'){
            $url['generated'] .= "paramtyp=mos_mix_mm&unit=2&mosmess=ja&";
        }else if(!empty($url['parameter_type']) && $url['parameter_type'] != 'reading'){
            $url['generated'] .= "paramtyp=mos_mix_mm&unit=&mosmess=ja&";
        }
        
        // Append the special parameters if there are any
        foreach($url['special_parameters'] as $special_parameters){
            $url['generated'] .= "$special_parameters=on&";
        }
        
        $url['generated'] .= "paramliste=";
        
        // Append the parameter lists
        foreach($url['parameters'] as $parameter){
            $url['generated'] .= "{$parameter}";
            $counter++;
            // print out an ampersand when it's the final parameter
            $url['generated'] .= ($counter == count($url['parameters'])) ? '&' : ',';
        }
        $counter = 0;
        
        // Append the default parameters
        foreach($url['defaults'] as $key=>$value){
            $url['generated'] .= "$key=$value";
            $counter++;
            $url['generated'] .= ($counter == count($url['defaults'])) ? '' : '&';
        }
        
        /**
         * logs the array in WEBROOT/tmp/logs/curl.log
         *  
         */
        CakeLog::write('curl', print_r($url, true));
        return $url['generated'];
    }
}
