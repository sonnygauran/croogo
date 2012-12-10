<?php
App::import('Lib', 'Meteomedia.Curl');

class Station extends WeatherphAppModel {

    public $name = 'Station';
    
    public $hasMany = array(
        'Reading' => array(
            'className'  => 'Reading',
            'foreignKey' => 'station_id',
        )
    );
    
    public function generate(){
        $url = "http://db.meteomedia.ch/abfrage.php?land=PHL&ortsinfo=ja&output=csv2&ortoutput=wmo6,name&aufruf=auto";
        $result = Curl::getData($url);
        
        $file = fopen(Configure::read('Data.stations'). date('Ydm') . '.csv', 'w');
        fwrite($file, $result);
        fclose($file);
        
        return true;

    }
    
    public function read(){
        
        $csv = file_get_contents(Configure::read('Data.stations'). date('Ydm') . '.csv');
        $rows = explode("\n", $csv);
        $stations = array();
        $headers = array();
        
        foreach(explode(';', $rows[0]) as $header){
            $headers[] = $header;
        }
        
        $headers = array_filter($headers);
        unset($rows[0]);
        
        
        foreach (array_values($rows) as $counter => $row){
            $fields = explode(';', $row);
            unset($fields[count($fields) - 1]);
            
            foreach($fields as $key => $value){
                $field = ($headers[$key] === 'id') ? 'sid': $headers[$key];
                $stations[$counter]['Station'][$field] = $value;
            }
            $stations[$counter]['Station']['id'] = null;

        }
        
        return $stations;
        
    }
    
    public function import(){
        $this->Behaviors->attach('Containable');
        
        $counter = 0;
        $stations = $this->read();
        
        foreach($stations as $station){
            if(!key_exists('sid', $station['Station'])) continue;
            
            $station_in_db = $this->find('count', array(
                'conditions' => array(
                    'sid' => $station['Station']['sid']
                )
            ));
            
            if(!$station_in_db){
                $this->create();
                $this->save($station);
                $counter++;
            }
            
        }

        return $counter;
    }
    
    
}
