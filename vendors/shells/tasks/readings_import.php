<?php
class ReadingsImportTask extends Shell {
    
    function execute(){
        
        $execution_time_start = microtime(TRUE);
        
        App::import('Model', 'Weatherph.Reading');
        $Reading = new Reading();
        
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
                            
                            // If exist overwrite to update
                            $Reading->id = $readings[0]['Reading']['id'];
                           
                            $Reading->save($data);
                           
                            echo "Updated count [$cntr_updated]\n";
                           
                        }else{
                            
                            // If not exist insert it to database
                            $cntr_inserted++;
                        
                            $data = array(
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
                                'rr1h' => $data[11],
                                'gl1h' => $data[12],
                                'sy' => $data[13],
                                'rain6' => $data[14],
                                'rh' =>$data[15],
                                'sy2' => $data[16],
                            );

                            $Reading->save($data);

                            echo "Inserted count [$cntr_inserted]\n";
                            
                        } 
                        
                    }
                    
                }                
            }

        }else{
            
            echo "File not found: $readings_csv_file, run the script to generate the csv file first.\n";
            
        }
        
        $execution_time_end = microtime(TRUE);
        
        $total_execution_time = $execution_time_end - $execution_time_start;
        
        echo "Execution Time (microseconds): " . $total_execution_time . "\n";
        
    }
   
}
?>