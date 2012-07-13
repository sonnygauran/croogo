<?php

class StationImportTask extends Shell{
    
    function execute(){
        App::import('Model', 'Weatherph.WeatherphStation');
        App::import('Model', 'Weatherph.Station');
        $WStation = new WeatherphStation();
        $Station = new Station();
        $stations = $WStation->fetch();
        $cntr_inserted = 0;
        
        $query = "SELECT count(*) as stations_count FROM `stations` WHERE wmo1 != ''";
        $result = $Station->query($query);
        
        $logs = '';
        
        foreach ($stations as $station) {
            
            if (!empty($station)) {
                
                $query = "SELECT `id` FROM `stations` WHERE `sid` = " . $station[0];
                $results = $Station->query($query);
                
                if(count($results) == 0){

                    $data = array();
                    // If not exist insert it to database
                    foreach($station as $value){
                        $data[] = "'".$value."'";
                    }

                    $data = implode(",", $data);

                    $query = "INSERT INTO `stations` VALUES (NULL, $data);";

                    if($Station->query($query)){
                        $cntr_inserted++;
                    }else{
                        //$logs = $Station->error . "\n";
                    }

                }
                
            }
        }
        
        if($cntr_inserted > 0){
        
            $logs = date('D M j H:i:s') . " :: New station(s) inserted [$cntr_inserted]\n";

        }else{
            $logs = date('D M j H:i:s') . " :: Nothing Happen!\n";
        }

        $this->out($logs);
        
    }
}