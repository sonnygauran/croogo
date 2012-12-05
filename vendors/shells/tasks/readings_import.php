<?php
class ReadingsImportTask extends Shell {
    
    function execute(){
        
        $execution_time_start = microtime(TRUE);
        
        App::import('Model', 'Weatherph.Reading');
        App::import('Model', 'Weatherph.Warning');
        App::import('Model', 'Weatherph.Station');
        $Reading = new Reading();
        $Warning = new Warning();
        $Station = new Station();
        
        //$csv_filename = date('Ydm');
        $csv_filename = date('Ymd') . ".csv";
        
        $readings_csv_file = Configure::read('Data.readings') . $csv_filename;
        
        if(file_exists($readings_csv_file)){
            
            $csvContent = file_get_contents($readings_csv_file);
            
            $rows = explode("\n", $csvContent);
            $headers = explode(';', $rows[0]);
            
//            echo print_r($headers, TRUE);
            
            unset($rows[0]);
            
            $cntr_inserted = $cntr_updated = 0;
            
            foreach($rows as $num => $row){
                
                $Reading->create();
                
                if(trim($row) != ''){
                    
                    $data = explode(';', $row);
                    
                    if(trim($data[7]) != '' && trim($data[5]) != '' && trim(strtolower($data[0])) != 'datum'){ //Check data if not NULL/EMPTY
                        $ort1 = explode('/', $data[3]);
                        unset($ort1[0]);
                        $station_name = implode('/', $ort1);

                        $station = $Station->find('first', array(
                            'conditions' => array(
                                'name' => $station_name,
                                'typ != ' => 'METAR' 
                            ),
                            'fields' => array('id'),
                        ));

                        $station_id = $station['Station']['id'];
                        if(!$station_id) continue;
                        
                        // Check database if this readings already exist
                        $readings = $Reading->find('all', array(
                        'fields' => array('id', 'datum', 'utc', 'min', 'ort1'),  
                        'conditions' => array( 
                            'datum =' => $data[0],
                            'utc =' => $data[1],
                            'min =' => $data[2],
                            'ort1 =' => $data[3],
                            ),
                        'order' => 'utc ASC',
                        'limit' => 1,
                        ));
                        
                        if(count($readings)>0){
                            
                            $cntr_updated++;
                            $gust = $data[14] * 1.852;
                            $rain =  $data[18];
                            $warning = $Warning->find('first', array(
                                'conditions' => array(
                                    'reading_id' => $readings[0]['Reading']['id']
                                )
                            ));
                            
                            
                            $thresholds = $this->checkThresholds($gust,  $rain, $station_id, $readings[0]['Reading']['id']);
                            // If exist overwrite to update
                            $Reading->id = $readings[0]['Reading']['id'];
                            
                            $data = array(
                                'Reading' => array(
                                    'datum' => date('Ymd', strtotime($data[0])),
                                    'utc' => $data[1],
                                    'min' => $data[2],
                                    'ort1' => $data[3],
                                    'dir' => $data[4],
                                    'ff' => $data[5],
                                    'g1h' => $data[6],
                                    'tl' => $data[7],
                                    'td' => $data[8],
                                    'tx' => $data[9],
                                    'tn' => $data[10],
                                    'rr10m' => $data[11],
                                    'rr1h' => $data[12],
                                    'gl1h' => $data[13],
                                    'g10' => $data[14],
                                    'sy' => $data[15],
                                    'rain6' => $data[16],
                                    'rh' =>$data[17],
                                    'sy2' => $data[18],
                                    'station_id' => $station_id
                                ),
                            );
                            
                            if($thresholds['rain'] || $thresholds['gust']){
                                $Warning->id = $warning['Warning']['id'];
                                echo print_r($warning, true);
                                echo "{$readings[0]['Reading']['id']}\n";
                                echo "Updating Warning on {$data['Reading']['ort1']}\n";
                                $Warning->save($thresholds);
                            }
                            
                            $Reading->save($data);
                           
                        }else{
                            
                            // If not exist insert it to database
                            $cntr_inserted++;
                            
                            if(key_exists(5, $data)){
                                $gust = $data[5] * 1.852;
                            }
                            if(key_exists(11, $data)){
                                $rain =  $data[11];
                            }
                            
                             $data = array(
                                'Reading' => array(
                                    'datum' => date('Ymd', strtotime($data[0])),
                                    'utc' => $data[1],
                                    'min' => $data[2],
                                    'ort1' => $data[3],
                                    'dir' => $data[4],
                                    'ff' => $data[5],
                                    'g1h' => $data[6],
                                    'tl' => $data[7],
                                    'td' => $data[8],
                                    'tx' => $data[9],
                                    'tn' => $data[10],
                                    'rr10m' => $data[11],
                                    'rr1h' => $data[12],
                                    'gl1h' => $data[13],
                                    'g10' => $data[14],
                                    'sy' => $data[15],
                                    'rain6' => $data[16],
                                    'rh' =>$data[17],
                                    'sy2' => $data[18],
                                    'station_id' => $station_id
                                ),
                            );
                            
                            
                            $thresholds = $this->checkThresholds($gust,  $rain, $station_id);

                            if($thresholds['rain'] || $thresholds['gust']){
                                echo "New Warning on {$data['Reading']['ort1']}\n";
                                $data['Warning'] = $thresholds;
                            }
                            
                            $Reading->saveAll($data);

                            
                        } 
                        
                    }
                    
                }                
            }
            
            echo "Updated [$cntr_updated] / Inserted [$cntr_inserted]\n";

        }else{
            
            echo "File not found: $readings_csv_file, run the script to generate the csv file first.\n";
            
        }
        
        $execution_time_end = microtime(TRUE);
        
        $total_execution_time = $execution_time_end - $execution_time_start;
        
        echo "Execution Time (microseconds): " . $total_execution_time . "\n";
        
    }
   
    public function checkThresholds($gust, $rain, $station_id, $reading_id = ''){
        $gust_severity = null;
        $rain_severity = null;
        
        if($gust >= 56 && $gust < 75){
            $gust_severity = "orange";
        }else if($gust >= 75 && $gust < 100){
            $gust_severity = "red";
        }else if($gust >= 100){
            $gust_severity = "violet";
        }
        
        if($rain >= 3 && $rain < 5){
            $rain_severity = "orange";
        }else if($rain >= 5 && $rain < 10){
            $rain_severity = "red";
        }else if($rain >= 10){
            $rain_severity = "violet";
        }

        $thresholds = array('gust' => $gust_severity, 'rain' => $rain_severity, 'station_id' => $station_id);
        if(!empty($reading_id)){
            $thresholds['reading_id'] = $reading_id;
        }
        
        return $thresholds;
    }
}
?>
