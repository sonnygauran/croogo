<?php


class DictionariesController extends WeatherphAppController {

    public $name = 'Dictionaries';
    public $uses = array('Block');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'default';
    }
    
    public function english(){
         $this->set('title_for_layout', 'English Dictionary');
    }
    
    
    public function filipino() {
         $this->set('title_for_layout','Filipino Dictionary');
        
    }


}