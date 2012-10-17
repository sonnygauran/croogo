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
        $og_image = array('property'=>'og:image','content'=>'http://alpha.weather.com.ph/theme/weatherph/img/logo.png');
        $og_title = array('property'=>'og:title','content'=>'Weather Philippines Foundation');
        $this->set(compact('meta_for_description','og_title','og_image'));
        $this->set('title_for_layout', 'English Dictionary');
    }
    
    
    public function filipino() {
        $meta_for_description = $this->description('description', 'List of terms and their definitions related to weather in Tagalog');
         $og_image = array('property'=>'og:image','content'=>'http://alpha.weather.com.ph/theme/weatherph/img/logo.png');
        $og_title = array('property'=>'og:title','content'=>'Weather Philippines Foundation');
        $this->set(compact('meta_for_description','og_title','og_image'));
        $this->set('title_for_layout','Filipino Dictionary');
        
    }


}