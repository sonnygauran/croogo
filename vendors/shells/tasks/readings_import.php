<?php
class ReadingsImportTask extends Shell {
    
    function execute(){
        
        $execution_time_start = microtime(TRUE);
        
        App::import('Model', 'Weatherph.Reading');
        $Reading = new Reading();
        
        $csv_filename = date('Ydm');
        
        $readings_csv_file = Configure::read('Data.readings') . $csv_filename . '.csv';;
        
        if(file_exists($readings_csv_file)){
            
            $csvContent = file_get_contents($readings_csv_file);
            
            $rows = explode("\n", $csvContent);
            $headers = explode(';', $rows[0]);
            
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
                                'datum' => $data[0],
                                'utc' => $data[1],
                                'min' => $data[2],
                                'ort1' => $data[3],
                                'dir' => $data[4],
                                'ff' => $data[5],
                                'g3h' => $data[6],
                                'tl' => $data[7],
                                'tn' => $data[8],
                                'rr' => $data[9],
                                'sy' => $data[10],
                                'rain3' => $data[11],
                                'rain6' => $data[12],
                                'rh' =>$data[13],
                                'sy2' => $data[14],
                            );

                            $Reading->save($data);

                            echo "Inserted count [$cntr_inserted]\n";
                            
                        } 
//                        
                    }
                    
                }                
            }

        }else{
            
            echo "File not found: $readings_csv_file, run the script to generate the csv file first.\n";
            
        }
        
        $execution_time_end = microtime(TRUE);
        
        $total_execution_time = $execution_time_end - $execution_time_start;
        
        $this->out("Execution Time: " . date('H:i:s', $total_execution_time));
        
    }
   
}
?>