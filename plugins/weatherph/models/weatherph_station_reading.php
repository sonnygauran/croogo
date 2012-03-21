<?php

/**
 * Acquires the measurements of Weather Stations
 */
class WeatherphStationReading extends WeatherphAppModel {

    public $name = 'WeatherphStationReading';
    public $useTable = false;

    public function find($conditions = null, $fields = array(), $order = null, $recursive = null) {
        include dirname(__FILE__) . '/auth.php';

        // INSERT CODE HERE
        
        // go through all the rows starting at the second row
        // remember that the first row contains the headings
        foreach ($weatherStations as $row) {
            $id = $row['id'];
            $name = $row['name'];
            $long = $row['lon'];
            $lati = $row['lat'];

            $stations[] = array(
                'id' => $id,
                'name' => $name,
                'coordinates' => array(
                    'longitude' => $long,
                    'latitude' => $lati,
                )
            );
        }
        curl_close($ch);

        return $stations;
    }

}