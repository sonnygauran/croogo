<?php

class StationFinderShell extends Shell {
 

    public $uses = array('Weatherph.NearestStation');

    function main() {
        ini_set('memory_limit', '4096M');
        $insert_count = 0;
        
	echo microtime() . "\n";
        echo date('Y-m-d G:i:s:a'). "\n";

        $counter = 0;
        App::import('Model', 'Weatherph.Station');
        App::import('Model', 'Weatherph.NearestStation');
        App::import('Model', 'Nima.NimaName');
        $Station = new Station();
        $NearestStation = new NearestStation();
        $NimaName = new NimaName();
        $values = array();
        $shortest = array();
        $shortest_counter = 0;
        $stations = $Station->find('all', array(
            'fields' => array('wmo1','lon','lat')
            
        ));
        $names = $NimaName->find('all', array(
            'fields' => array('id','long','lat')
        ));

        echo "Starting...\n";
        foreach($names as $name){
            foreach($stations as $station){
                $values[$counter]['station_id'] = $station['Station']['wmo1'];
                $values[$counter]['reference'] = $name['Name']['id'];
                $sampdistance= $this->computeDistance($name['Name']['long'], $station['Station']['lon'], $name['Name']['lat'], $station['Station']['lat']);
                $values[$counter]['distance'] = $sampdistance;
                $counter++;
            }
			$calculated = $this->findStation($values);
			$shortest[$shortest_counter] = $calculated;
			$shortest_counter++;
            $counter = 0;
            
        }

		foreach ($shortest as $short) {
            $query = "insert into nearest_stations (distance, station_id, reference ) values ( ";
            foreach($short as $value){
                $query .= "'$value'";
                $query .= ($counter == count($short)-1) ? '' : ',';
                $counter++;
            }
            $counter = 0;
            $query .= ");";
			$NearestStation->query($query);
                        $insert_count++;
                        if($insert_count % 10 == 0) echo "$insert_count \n";
                        
        }

	echo microtime() . "\n";
        echo date('Y-m-d G:i:s:a'). "\n";
    }
    
    function computeDistance($lon1, $lon2, $lat1, $lat2){
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515 * 1.609344;
        return $miles;
    }

	function findStation($array){
		$station['distance'] =100000;
		foreach($array as $value){
			if ($value['distance'] < $station['distance']){
				$station['station_id'] = $value['station_id'];
				$station['reference'] = $value['reference'];
				$station['distance'] = $value['distance'];
			} 
		}
		return $station;


	}
}
