<?php

App::import('Model', 'Nima.NimaName');

class DmoForecast extends WeatherphAppModel {
    
    public $uses = array('Nima.NimaName');

    public function get($conditions = NULL, $fields = array()){
        
        $this->log('test');

        $this->log(print_r($fields['conditions']['id'],true));
        
//        $dmo_result = array();
//        
//        $NimaName = new NimaName();
//        $keyword = 33704;
//        $locations = $NimaName->find('all', array('fields' => array('id' ,'lat', 'long'),  'conditions' => array( 'id =' => '%'.$keyword.'%')));
//       
//        debug($locations);
     
    }    
    
}