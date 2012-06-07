<?php

/**
 * @author Jaggy Gauran
 *  
 * Meteomedia Plugin - XML 
 * 
 */
class Xml {
    
    /**
     * write all the properties supplied
     * 
     * @param type $properties
     * @return type 
     */
    private static function writeProperties($properties){
        $output = "";
        
        if(!empty($properties)){
            foreach($properties as $key => $value) $output .= " $key='$value'";
        }
        
        return $output;
    }
    
    /**
     *
     * write the data supplied
     *  can generate nested or single value content
     * 
     * @param type $values
     * @return type 
     * 
     */
    private static function writeData($values){
        $output = "";
        
        if(is_array($values)){ 
            foreach($values as $value) $output .= $value;
        }else{
            $output = $values;
        }
        
        return $output;
    }


    
    /**
     *
     * Dynamically create tags.
     * Can contain arrayed values
     * 
     * @param type $name
     * @param type $properties
     * @param type $values
     * @return type 
     */
    public static function createTag($name, $properties, $values = ''){
        return '<'. $name . self::writeProperties($properties) .'>'. self::writeData($values) .'</'.$name.'>';
    }
    
    
    /**
     * Lazy Tagging
     * 
     * @param type $dump 
     */
    public static function dumpsterDive($dump){
        $treasure = "";
        $cart = array();
        
        foreach($dump as $bag => $garbage){
            
            if(empty($garbage['properties']))$garbage['properties'] = '';
            if(empty($garbage['values']))$garbage['values'] = '';
            
            if(self::isGarbageBag($garbage['values'])){
                $cart[count($cart)] = self::dumpsterDive($garbage['values']);
                $treasure .= self::createTag($bag, $garbage['properties'], $cart);
            }else{
                $treasure .= self::createTag($bag, $garbage['properties'], $garbage['values']);
            }
            $cart = array();
        }
        return $treasure;
    }
    
    private static function isGarbageBag($content){
        return is_array($content);
    }
    

}