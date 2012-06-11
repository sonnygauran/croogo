<?php

class NimaNamesController extends NimaAppController {

    public $name = 'NimaNames';

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'default';
    }
    
    public function index() {
        App::import('Model','Nima.NimaName');
        $Name = new NimaName();
        $name = $Name->findBySortNameRo('PROVIDENTVILLAGES');
        debug($name);
    }
}