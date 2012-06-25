<?php

App::import('Lib', 'Meteomedia.Abfrage');
App::import('Lib', 'Meteomedia.Curl');

class StationReadingsTask extends Shell{
    
    public $uses = array('Station');
    
    function execute(){
        $csv = "";
        $counter = 1;
        $file_name = Configure::read('Data.readings'). date('Ydm') . '.csv';
        
        echo "Path: " . Configure::read('Data.readings') . "\n";
        
        if(!is_dir(Configure::read('Data.readings'))){
            echo "Cannot find " . Configure::read('Data.readings') . "\n";
            echo "Create the directory or change the location on your settings.private.yml\n";
            exit;
        }
        
        echo "Start Date [yyyymmdd]:";
        $startdate = trim(fgets(STDIN));
        echo "End Date [yyyymmdd]: ";
        $enddate = trim(fgets(STDIN));
        
        $time_format = array(
            'time_resolution' => "10m",
            'start_date'      => $startdate,
            'end_date'        => $enddate,
            'start_hour'      => 00,
            'end_hour'        => 00,
        );
        
        
        $this->out("Generating Station Readings CSV");
        $ids = $this->Station->find('all', array( 'fields' => 'sid' ));
        
        foreach($ids as $id){
            $stationId = $id['Station']['sid'];
            $Abfrage = new Abfrage($stationId);
            
            $url = $Abfrage->generateURL($time_format, array(
                'Temperature' => array(
                    'low', 'min'
                ),
                'Wind' => array(
                    'speed', 'direction'
                ),
                'Gust' => array(
                    '3 hours'
                ),
                'Rainfall' => array(
                    'Period', '3 hours', '6 hours'
                ),
                'Weather Symbols' => array(
                    'Set 1','Set 2'
                ),
                'Humidity'
            ));
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
        
         $this->out("Generated Stations CSV: [$file_name]");
        
        
        
        
    }
}
