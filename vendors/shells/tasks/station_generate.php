<?php
App::import('Lib', 'Meteomedia.Curl');

class StationGenerateTask extends Shell{

    public function execute(){
        $this->out('Generating Station CSV');
        $file_name = Configure::read('Data.stations'). date('Ydm') . '.csv';
        $url = "http://db.meteomedia.ch/abfrage.php?land=PHL&ortsinfo=ja&output=csv2&ortoutput=wmo6,name&aufruf=auto";
        $this->out('URL: '.$url);
        $result = NULL;
        $result = Curl::getData($url);
        fopen($file_name, 'w');
        $file = fopen($file_name, 'w') or die("can't open file");
        fwrite($file, $result);
        fclose($file);
        
        $this->out("Generated Stations CSV: [$file_name]");
    }
    
}