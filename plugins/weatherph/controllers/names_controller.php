<?php

class NamesController extends WeatherphAppController {
    
    public $name = 'Names';
    public $uses = array('Nima.NimaName');
    
    public function index() {
        //App::import('Model','Nima.NimaName');
        $Name = new NimaName();
        $name = $Name->findBySortNameRo('PROVIDENTVILLAGES');
        //debug($name);
        //debug($this->paginate());
        $this->set('names',$this->paginate());
    }
}