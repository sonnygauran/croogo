<?php


class DictionariesController extends WeatherphAppController {

    public $name = 'Dictionaries';
    public $uses = array('Block');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'default';
    }
    
    public function english(){
        
    }
    
    
    public function filipino() {
        
    }


}