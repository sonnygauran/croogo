<?php


class FoundersController extends WeatherphAppController {

    public $name = 'Founders';
    public $uses = array('Block');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'default';
    }
    
    public function meteomedia() {
      
    }

    public function aboitiz() {
        
    }

    public function unionbank() {

    }
    
    public function about(){
        
    }

}