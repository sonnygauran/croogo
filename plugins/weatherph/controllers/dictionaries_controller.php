<?php


class DictionariesController extends WeatherphAppController {

    public $name = 'Dictionaries';
    public $uses = array('Block');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'default';
    }
    
    public function english(){
        $meta_for_description = $this->description('description', 'WeatherPhilippines');
        $meta_for_keywords = $this->keywords('keywords', 'WeatherPhilippines, 
            weather, philippines, weather philippines, dictionary, english');
        $this->set(compact('meta_for_description','meta_for_keywords'));
        $this->set('title_for_layout', 'English Dictionary');
    }
    
    
    public function filipino() {
        $meta_for_description = $this->description('description', 'WeatherPhilippines');
        $meta_for_keywords = $this->keywords('keywords', 'WeatherPhilippines, 
            weather, philippines, weather philippines, dictionary, tagalog');
        $this->set(compact('meta_for_description','meta_for_keywords'));
        $this->set('title_for_layout','Filipino Dictionary');
        
    }


}