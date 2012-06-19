<?php

class StationShell extends Shell {
    

    function main() {
        App::import('Model', 'Weatherph.Station');
        $Station = new Station();
        $stations = array_filter($Station->find('all'));
        $counter = 0;
        
        foreach ($stations as $station) {
            $query = "insert into stations  values (";
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

