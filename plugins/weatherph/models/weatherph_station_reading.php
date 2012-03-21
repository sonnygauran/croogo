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
        //$stations = array('a'=>'b');
        
        $url = 'http://karten.meteomedia.ch/db/abfrage.php?stationidstring=980001&datumstart=20120321&datumend=20120321&tl=on&td=on&tx=on&tn=on&t5=on&dir=on&ff=on&g1h=on&g3h=on&qff=on&qnh=on&qfe=on&ap=on&www=on&vis=on&n=on&l=on&metarwx=on&cov=on&clcmch=on&clg=on&rr10m=on&rr1h=on&rain3=on&rain6=on&rain12=on&sno=on&new=on&s10=on&sh=on&ss24=on&gl10=on&gl1h=on&gl24=on&stationsreihe=on&output=csv2&ortoutput=wmo6,name&aufruf=auto';

        $stations = array();
        $location = $url;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $location);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, "{$karten['username']}:{$karten['password']}");
        curl_setopt($ch, CURLOPT_USERAGENT, "Weather.com.ph Client 1.0");
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); //times out after 10s 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $result = curl_exec($ch);
        $rows = explode("\n", $result);
        //$numrow=count($rows);

        $headers = explode(';', $rows[0]);
        //print_r($headers);

        unset($rows[0]);

        $station_map = array();
        foreach ($rows as $row) {
            $row = explode(';', $row);
            //print_r($row);

            $orgs = array();
            $current = array();
            foreach ($row as $key => $field) {
                $index = $headers[$key];

                if (strlen($index) == 0) {
                    // IGNORE empty indexes
                } else {
                    $current[$index] = $field;
                }
            }

            $cleanData = true;
//            foreach (array('id', 'name', 'lon', 'lat') as $validIndex) {
//                if (!key_exists($validIndex, $current)) {
//                    $cleanData = false;
//                    break;
//                }
//            }

            // if active

            if ($cleanData) {
                $station_map[] = $current;
            }
        }

        $readings = array();
        if (is_string($conditions) AND $conditions == 'all' AND empty($fields)) {
            // default behavior.
            $readings = $station_map;
        } else if (is_string($conditions) AND $conditions == 'first') {
            $readings = reset($station_map);
        } else if (is_string($conditions) AND is_array($fields) AND key_exists('conditions', $fields)) {
            // there are defined conditions
//            $provider = 'meteomedia';
//            if (key_exists('provider', $fields['conditions'])) {
//                $provider = $fields['conditions']['provider'];
//            }
//            foreach ($station_map as $stationItem) {
//                if ($stationItem['provider'] == $provider) {
//                    $weatherStations[] = $stationItem;
//                }
//            }
            $readings = $station_map;
        }
        
        // go through all the rows starting at the second row
        // remember that the first row contains the headings
//        foreach ($weatherStations as $row) {
//                $id = $row['id'];
//                $name = $row['name'];
//                $long = $row['lon'];
//                $lati = $row['lat'];
//
//                $stations[] = array(
//                    'id' => $id,
//                    'name' => $name,
//                    'coordinates' => array(
//                        'longitude' => $long,
//                        'latitude' => $lati,
//                    )
//                );
//        }
        curl_close($ch);

        return $readings;
    }

}