<?php
class ReadingsTask extends Shell {
   
   function execute() {
       
        App::import('Model', 'Weatherph.WeatherphStation');
        App::import('Model', 'Weatherph.Station');
        App::import('Lib', 'Meteomedia.Abfrage');
        App::import('Lib', 'Meteomedia.Curl');
//        
//        $WeatherphStation = new Station();
//        $stations = $WeatherphStation->find('all');
//       
//        $stationsId = Set::extract($stations, '{n}.Station.wmo1');
//
//        $Abfrage = new Abfrage($stationsId);
//        
//        //Grab stations readings  
//        $url = $Abfrage->generateURL(
//            $WeatherphStation->generateDate('reading', '10m'),
//            array(
//                'Temperature' => array('low'),
//                'Wind' => array('speed', 'direction'),
//                'Gust' => array('1 hour', '3 hours'),
//                'Rainfall' => array('Period', '3 hours', '6 hours'),
//                'Weather Symbols' => array('Set 1', 'Set 2'),
//                'Humidity'
//            )
//        );
//
//        //debug($url);exit;
//
//        $curlResults = NULL;
//        $curlResults = Curl::getData($url, 60);
//        $this->log($curlResults);
//        echo $curlResults;
//        exit;
        $curlResults = file_get_contents(APP_PATH.'/data/readings/readings.csv');
        
        $rows = explode("\n", $curlResults);
        $headers = explode(';', $rows[0]);
        
        //$this->log(print_r($rows, TRUE));exit;

        unset($rows[0]);
        
        ini_set('memory_limit', '512M');

        $arrayResults = array();
        foreach ($rows as $key => $row) {
            if (trim($row) != '') {
                $params = explode(';', $row);
                //$this->log(print_r($params, TRUE));
                foreach ($params as $key2 => $param) {
                    if ($headers[$key2] != '') {
                        $fieldName = $headers[$key2];
                        $uniqueKey = $key;
                        $arrayResults[$uniqueKey][$fieldName] = trim($param);
                    }
                }
            }
        }
        
        //exit;
        //$this->log(print_r($arrayResults, TRUE));exit;
        
        App::import('Model', 'Weatherph.Reading');
        $Reading = new Reading();
        
        foreach($arrayResults as $result){
            //echo print_r(compact('result'), true);
            $Reading->create();
            $keys = array(
                'datum',
                'utc',
                'min',
                'ort1',
                'dir',
                'ff',
                'g3h',
                'tl',
                'rr',
                'sy',
                'rain6',
                'rh',
                'sy2',
                'rain3',
                'g6h',
            );
            
            foreach ($keys as $key) {
                if (!key_exists($key, $result)) {
                    $result[$key] = '';
                }
            }
                
            $data = array(
                'datum' => date('Y-m-d',strtotime($result['Datum'])),
                'utc' => $result['utc'],
                'min' => $result['min'],
                'ort1' => $result['ort1'],
                'dir' => $result['dir'],
                'ff' => $result['ff'],
                'g3h' => $result['g3h'],
                'tl' => $result['tl'],
                'rr' => $result['rr'],
                'sy' => $result['sy'],
                'rain6' =>$result['rain6'],
                'rh' => $result['rh'],
                'sy2' => $result['sy2'],
                'rain3' =>$result['rain3'],
                'g6h' => '',
            );
            $Reading->save($data);
            
        }
        
        echo "\nDone!\n";
   }
}
?>