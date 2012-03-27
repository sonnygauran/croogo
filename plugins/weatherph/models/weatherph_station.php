<?php

class WeatherphStation extends WeatherphAppModel {

    public $name = 'WeatherphStation';
    public $useTable = false;

    public function find($conditions = null, $fields = array(), $order = null, $recursive = null) {
        include dirname(__FILE__) . '/auth.php';

        $url = "http://karten.meteomedia.ch/db/abfrage.php?land=PHL&ortsinfo=ja&datumstart=20120313&datumend=20120313&output=csv2&ortoutput=wmo6,name&aufruf=auto";

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
        $this->log($rows);
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
                } else if ($index == 'org') { // Originator
                    if (!in_array($field, $orgs)) {
                        $orgs[] = $field;
                    }

                    if ($field == 'JRG') {
                        $current['provider'] = 'meteomedia';
                    } else {
                        $current['provider'] = 'pagasa';
                    }
                } else {
                    $current[$index] = $field;
                }
            }
            $this->log(print_r($current, true));
            $dirtyCount = 0;
            foreach (array('id', 'name', 'lon', 'lat') as $validIndex) {
                if (!key_exists($validIndex, $current)) {
                    $dirtyCount++;
                }
            }
            
            if (key_exists('aktiv', $current) AND key_exists('mos_ez_mm', $current)) {
                if ($current['mos_ez_mm'] == 0) {
                    $dirtyCount++;
                }
            }


            // if active
            if ($dirtyCount == 0) {
                $station_map[] = $current;
            }
        }
        
        $weatherStations = array();
        if (is_string($conditions) AND $conditions == 'all' AND empty($fields)) {
            // default behavior.
            $weatherStations = $station_map;
        } else if (is_string($conditions) AND $conditions == 'first') {
            $weatherStations = reset($station_map);
        } else if (is_string($conditions) AND is_array($fields) AND key_exists('conditions', $fields)) {
            // there are defined conditions
            $provider = 'meteomedia';
            if (key_exists('provider', $fields['conditions'])) {
                $provider = $fields['conditions']['provider'];
            }
            foreach ($station_map as $stationItem) {
                if (key_exists('provider', $stationItem) AND $stationItem['provider'] == $provider) {
                    $weatherStations[] = $stationItem;
                }
            }
            //$weatherStations = $station_map;
        }

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