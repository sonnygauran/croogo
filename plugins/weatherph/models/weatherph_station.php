<?php

class WeatherphStation extends WeatherphAppModel {
    public $name = 'WeatherphStation';
    public $useTable = false;

    public function find($conditions = null, $fields = array(), $order = null, $recursive = null) {
        $stations = array();

        if (is_string($conditions) AND $conditions == 'all') {
            $stations = array(
                array(
                    'id'          => 1,
                    'name'        => 'Station Name',
                    'coordinates' => array(
                        'longitude' => 1,
                        'latitude'  => 1,
                    )
                ),
            );
        }
        
        return $stations;
    }
}