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

}

?>
