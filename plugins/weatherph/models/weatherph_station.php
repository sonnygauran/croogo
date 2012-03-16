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
            
            unset($rows[0]);
            //echo print_r($rows, true);
 
            // go through all the rows starting at the second row
            // remember that the first row contains the headings
            foreach ($rows as $row)
            {
                    $temp = explode(";", $row);
                    
                    $cleanData = true;
                    foreach (array(0, 1, 17, 16) as $validIndex) {
                        if (!key_exists($validIndex, $temp)) {
                            $cleanData = false;
                            break;
                        }
                    }
                    
                    if ($cleanData) {
                        $id = $temp['0'];
                        $name= $temp['1'];
                        $long = $temp['17'];
                        $lati = $temp['16'];
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