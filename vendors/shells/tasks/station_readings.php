<?php

App::import('Lib', 'Meteomedia.Abfrage');
App::import('Lib', 'Meteomedia.Curl');

class StationReadingsTask extends Shell{
    
    public $uses = array('Station');
    
    function execute(){
        
        $execution_time_start = microtime(TRUE);
        
        $csv = "";
        $counter = 1;
        $file_name = Configure::read('Data.readings'). date('Ymd') . '.csv';
        $date = date('Ymd');
        $start_hour = date('H') - 2;
        $end_hour = date('H');
        
        echo "Path: " . Configure::read('Data.readings') . "\n";
        
        if(!is_dir(Configure::read('Data.readings'))){
            echo "Cannot find " . Configure::read('Data.readings') . "\n";
            echo "Create the directory or change the location on your settings.private.yml\n";
            exit;
        }
        
        echo $date . "\n";

        $time_format = array(
            'time_resolution' => "10m",
            'start_date'      => $date,
            'end_date'        => $date,
            'start_hour'      => $start_hour,
            'end_hour'        => $end_hour,
        );
        
        
        $this->out("Generating Station Readings CSV");
        $ids = $this->Station->find('all', array( 'fields' => 'sid' ));
        
        foreach($ids as $id){
            $stationId = $id['Station']['sid'];
            $Abfrage = new Abfrage($stationId);
            
            $url = $Abfrage->generateURL($time_format, array(
                'Temperature' => array(
                    'dew point', 'low', 'min', 'max'
                ),
                'Wind' => array(
                    'speed', 'direction'
                ),
                'Gust' => array(
                    '1 hour'
                ),
                'Rainfall' => array(
                    '1 hour', '6 hours'
                ),
                'Weather Symbols' => array(
                    'Set 1','Set 2'
                ),
                'Global Radiation' => array(
                    '1 hour'
                ),
                'Humidity'
            ), false);
            $curlResults = NULL;
            $curlResults = Curl::getData($url);
            
            $csv .= $curlResults; 
            echo "Done $counter\n";
            $counter++;
        }

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
