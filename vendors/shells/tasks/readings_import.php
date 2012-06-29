<?php
class ReadingsImportTask extends Shell {
    
    function execute(){
        
        App::import('Model', 'Weatherph.Reading');
        $Reading = new Reading();
        
        //echo 'CSV Readings Date [yyyyddmm]:';
        //$csv_filename = trim(fgets(STDIN));
        
        $readings_csv_file = Configure::read('Data.readings') . $csv_filename . '.csv';;
        
        if(file_exists($readings_csv_file)){
            
            $csvContent = file_get_contents($readings_csv_file);
            
            $rows = explode("\n", $csvContent);
            $headers = explode(';', $rows[0]);
            
            unset($rows[0]);
            
            $cntr = 0;
            
            foreach($rows as $num => $row){
                
                $Reading->create();
                
                if(trim($row) != ''){
                    
                    $data = explode(';', $row);
                    
                    if(trim($data[7]) != '' && trim($data[5]) != '' && trim(strtolower($data[0])) != 'datum'){ //Check data if not NULL/EMPTY
                        
                        $cntr++;
                        
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
                    
                        echo "Data inserted count [$cntr]\n";
                    }
                    
                }                
            }

        }else{
            echo "File not found: $readings_csv_file\n";
        }
        
    }
   
}
?>