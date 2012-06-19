<?php
App::import('Lib', 'Meteomedia.Curl');

class Station extends WeatherphAppModel {

    public $name = 'Station';
    public $useDbConfig='station';

    public function find($conditions = null, $fields = array(), $order = null, $recursive = null) {
        $counter = 0;
        $url = "http://abfrage.meteomedia.ch/manila.php?land=PHL&ortsinfo=ja&output=csv2&ortoutput=wmo6,name&aufruf=auto";
        
        $stations = array();
        
        $result = NULL;
        $result = Curl::getData($url);

            
        
        $rows = explode("\n", $result);
        
        $headers = explode(';', $rows[0]);
        
        unset($rows[0]);

        foreach ($rows as $row) {
            
            $row = explode(';', $row);
            
            foreach ($row as $key => $field) {
                $index = $headers[$key];
                if(strlen($index) == 0) continue;
                $stations[$counter][$index] = $field;
            }
            $counter++;

        }
        
        unset($stations[count($stations)-1]);

        return $stations;
    }
}
