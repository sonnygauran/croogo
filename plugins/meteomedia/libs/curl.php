<?php

/**
 * @author Jaggy Gauran
 *  
 * Meteomedia Plugin - Curl 
 * 
 */
class Curl{
    
    /**
     * 
     * Usage: Curl::getData($url);
     * 
     * @param type $url
     * @return type 
     */
    public static function getData($url){
        $curlResults = array();
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, "{$karten['username']}:{$karten['password']}");
        curl_setopt($ch, CURLOPT_USERAGENT, "Weather.com.ph Curl Client 1.0");
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); //times out after 10s 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $curlResults = curl_exec($ch);
        curl_close($ch);
        
        return $curlResults;
    }
    
    
}