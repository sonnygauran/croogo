<?php

class WarningsController extends WeatherphAppController{
    
    public $name = 'Warnings';
    
    public function plot(){
        $this->Warning->Behaviors->attach('Containable');
        
        $warnings = $this->Warning->find('all', array(
        ));
        debug($warnings);
        exit;
    }
}