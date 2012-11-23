<?php

class StationsController extends WeatherphAppController {

    public $name = 'Stations';

    public function admin_index() {

        $fields = array('id', 'name', 'wmo1', 'lat', 'lon', 'org', 'webname', 'webaktiv');
        $this->paginate = array(
            'fields' => $fields,
            'contain' => false,
            'limit' => 30
        );
        $stations = $this->paginate('Station');
        $this->set(compact('stations', 'fields'));
    }
    
    public function admin_update(){
        
        $insertions = $this->Station->generate();
        $insertions = $this->Station->read();
        $insertions = $this->Station->import();
        $message = ($insertions) ? "Inserted {$insertions} new Stations!" : "Stations up to date!";
        $this->Session->setFlash($message);
        $this->redirect(array('plugin' => 'weatherph','controller' => 'stations', 'action' => 'index', 'admin' => true));
        
    }

}

?>
