<?php

class StationImportTask extends Shell{
    
    function execute(){
        App::import('Model', 'Weatherph.WeatherphStation');
        App::import('Model', 'Weatherph.Station');
        $WStation = new WeatherphStation();
        $Station = new Station();
        $stations = $WStation->fetch();
        $counter = 0;
        
        foreach ($stations as $station) {
            if (!empty($station)) {
                $query = "insert into stations  values (NULL, ";

                foreach($station as $value){
                    $query .= "'$value'";
                    $query .= ($counter == count($station)-1) ? '' : ',';
                    $counter++;
                }

                $counter = 0;
                $query .= ");";

                $Station->query($query);
            }
        }
    }
}