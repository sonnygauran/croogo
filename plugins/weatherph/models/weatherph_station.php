<?php

class WeatherphStation extends WeatherphAppModel {
    public $name = 'WeatherphStation';
    public $useTable = false;
    
    
    

    public function find($conditions = null, $fields = array(), $order = null, $recursive = null)
    {
        include dirname(__FILE__).'/auth.php';
        
        
        
        $url = "http://karten.meteomedia.ch/db/abfrage.php?land=PHL&ortsinfo=ja&datumstart=20120313&datumend=20120313&output=csv2&ortoutput=wmo6,name&aufruf=auto";
        
            $stations = array();
          $location= $url;
            
            $ch = curl_init (); 
            curl_setopt($ch,CURLOPT_URL,$location); 
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
            curl_setopt($ch,CURLOPT_USERPWD,"{$karten['username']}:{$karten['password']}"); 
            curl_setopt($ch,CURLOPT_USERAGENT,"Weather.com.ph Client 1.0"); 
            curl_setopt($ch, CURLOPT_TIMEOUT, 10); //times out after 10s 
            curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1); 
                
            $result = curl_exec ($ch); 
            $rows = explode("\n", $result);
            //$numrow=count($rows);
            
           $headers = explode(';',$rows[0]);
           //print_r($headers);
            
            unset($rows[0]);
            
            $station_map = array();
            foreach ($rows as $row)
            {
                $row = explode(';', $row);
                //print_r($row);
                
                $current = array();
                foreach ($row as $key => $field)
                {
                    $current[$headers[$key]] = $row[$key];
                }
                $station_map[] = $current;
            }
            
            // go through all the rows starting at the second row
            // remember that the first row contains the headings
            foreach ($station_map as $row)
            {
                
//                    $temp = explode(";", $row);
                    
                    $cleanData = true;
                    foreach (array('id', 'name', 'lon', 'lat') as $validIndex) {
                        if (!key_exists($validIndex, $row)) {
                            $cleanData = false;
                            break;
                        }
                    }
                    
                    // if active
                    
                    if ($cleanData) {
                        $id = $row['id'];
                        $name= $row['name'];
                        $long = $row['lon'];
                        $lati = $row['lat'];
                        //echo print_r(compact('id','name','long','lati'), true).'<br />';
                        
                        
                        if (is_string($conditions) AND $conditions == 'all')
                        {

                            $stations[] = array(
                                'id'          => $id,
                                'name'        => $name,
                                'coordinates' => array(
                                    'longitude' => $long,
                                    'latitude'  => $lati,
                                )
                            );
                        }


                    }
                    //echo $rows[$i];
                    //echo "1.$id 2.$name 3.$long 4.$lati  <br/>";
                    
            }
            curl_close($ch);
            
        //$stations = array();

        //if (is_string($conditions) AND $conditions == 'all')
        //{
        //    if ($rows <= $numrow)
        //    {
        //    
        //    $stations = array(
        //        array(
        //            'id'          => $temp['0'],
        //            'name'        => $name,
        //            'coordinates' => array(
        //                'longitude' => $long,
        //                'latitude'  => $lati,
        //            )
        //        )
        //    );
        //    }
        //}
            
        return $stations;
    }
}