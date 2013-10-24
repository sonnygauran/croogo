<?php

App::import('Lib', 'Meteomedia.Abfrage');
App::import('Lib', 'Meteomedia.Curl');

class StationReadingsTask extends Shell{
    
    public $uses = array('Station');
    
    function execute(){
        $this->out('Importing Station data from CSV');
        $execution_time_start = microtime(TRUE);
        
        $counter = 1;
        $file_name = Configure::read('Data.readings'). date('Ymd') . '.csv';
        $date = date('Ymd');
        $start_hour = date('H') - 2;
        $end_hour = date('H');
        
        $this->out("Path: " . Configure::read('Data.readings'));
        
        if(!is_dir(Configure::read('Data.readings'))){
            $this->out("Cannot find " . Configure::read('Data.readings'));
            $this->out("Create the directory or change the location on your settings.private.yml");
            exit;
        }
        
        $this->out($date);

        $time_format = array(
            'time_resolution' => "10m",
            'start_date'      => $date,
            'end_date'        => $date,
            'start_hour'      => $start_hour,
            'end_hour'        => $end_hour,
        );
        
        
        $this->out("Generating Station Readings CSV");
        $ids = $this->Station->find('all', array( 'fields' => 'sid' ));
        
        $stations = array();
        foreach($ids as $id){
            $stations[] = $id['Station']['sid'];
        }

        $this->out('Station IDs: '.implode(', ', $stations));
        
        $Abfrage = new Abfrage($stations);

        $url = $Abfrage->generateURL($time_format, array(
            'Temperature' => array(
                'dew point', 'low', 'min', 'max'
            ),
            'Wind' => array(
                'speed', 'direction'
            ),
            'Gust' => array(
                '1 hour', '10 minutes'
            ),
            'Rainfall' => array(
                '1 hour', '6 hours', '10 minutes'
            ),
            'Weather Symbols' => array(
                'Set 1','Set 2'
            ),
            'Global Radiation' => array(
                '1 hour'
            ),
            'Humidity'
        ), false);
        
        $this->out('URL: '.$url);
        $csv = Curl::getData($url, 200);
        
        fopen($file_name, 'w');
        $file = fopen($file_name, 'w') or die("can't open file");
        fwrite($file, $csv);
        fclose($file);

        $execution_time_end = microtime(TRUE);
        
        $this->out("Generated Stations CSV: [$file_name]");
        
        $total_execution_time = $execution_time_end - $execution_time_start;
        
        $this->out("Execution Time: " . date('H:i:s', $total_execution_time));
        
        
    }
}
