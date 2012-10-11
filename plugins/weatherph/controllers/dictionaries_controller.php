<?php


class DictionariesController extends WeatherphAppController {

    public $name = 'Dictionaries';
    public $uses = array('Block');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'default';
    }
    
    public function english(){
        $meta_for_description = $this->description('description', 'List of terms and their definitions related to weather in English');
        $this->set(compact('meta_for_description'));
        $this->set('title_for_layout', 'English Dictionary');
    }
    
    
    public function filipino() {
        $meta_for_description = $this->description('description', 'List of terms and their definitions related to weather in Tagalog');
        $this->set(compact('meta_for_description'));
        $this->set('title_for_layout','Filipino Dictionary');
        
    }


}