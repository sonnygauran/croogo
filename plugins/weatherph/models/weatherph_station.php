<?php

class WeatherphStation extends WeatherphAppModel {
    public $name = 'WeatherphStation';
    public $useTable = false;
    

    public function find($conditions = null, $fields = array(), $order = null, $recursive = null) {
        $stations = array();

        if (is_string($conditions) AND $conditions == 'all') {
            $stations = array(
                array(
                    'id'          => 26481,
                    'name'        => 'Iloilo',
                    'coordinates' => array(
                        'longitude' => 122.5667,
                        'latitude'  => 10.7,
                    )
                ),
                array(
                    'id'          => 26437,
                    'name'        => 'Alabat',
                    'coordinates' => array(
                        'longitude' => 122.0167,
                        'latitude'  => 14.0833,
                    )
                ),
            );
        }
        
        return $stations;
    }
}