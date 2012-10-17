<?php


class DictionariesController extends WeatherphAppController {

    public $name = 'Dictionaries';
    public $uses = array('Block');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'default';
    }
    
    public function english(){
        $meta_for_description = $this->description('description', 'Know the different definitions on the subject of meteorology.');
        $og_image = array('property'=>'og:image','content'=>'http://alpha.weather.com.ph/theme/weatherph/img/logo.png');
        $og_title = array('property'=>'og:title','content'=>'Weather Philippines Foundation');
        $og_description = array('property'=>'og:description','content'=> "Know the different definitions on the subject of meteorology.");
        $this->set(compact('meta_for_description','og_title','og_image','og_description'));
        $this->set('title_for_layout', 'English Dictionary');
        
    }
    
    
    public function filipino() {
        $meta_for_description = $this->description('description', "Alamin ang iba't ibang mga kahulugan sa paksa ng meteorolohiya.");
        $og_image = array('property'=>'og:image','content'=>'http://alpha.weather.com.ph/theme/weatherph/img/logo.png');
        $og_description = array('property'=>'og:description','content'=> "Alamin ang iba't ibang mga kahulugan sa paksa ng meteorolohiya.");
        $og_title = array('property'=>'og:title','content'=>'Weather Philippines Foundation');
        $this->set(compact('meta_for_description','og_title','og_image','og_description'));
        $this->set('title_for_layout','Filipino Dictionary');
        
    }


}