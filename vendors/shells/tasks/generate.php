<?php

App::import('Lib', 'Meteomedia.Curl');
class GenerateTask extends Shell{
    
    function execute(){

        $file_name = "data/stations/". date('Ydm-His') . '.csv';
        $url = "http://abfrage.meteomedia.ch/manila.php?land=PHL&ortsinfo=ja&output=csv2&ortoutput=wmo6,name&aufruf=auto";
        
        $result = NULL;
        $result = Curl::getData($url);
        fopen($file_name, 'w');
        $file = fopen($file_name, 'w') or die("can't open file");
        fwrite($file, $result);
        fclose($file);
        
        $this->out("Generated Stations CSV: [$file_name]");
    }
    
}