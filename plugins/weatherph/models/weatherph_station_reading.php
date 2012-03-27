<?php

/**
 * Acquires the measurements of Weather Stations
 */
class WeatherphStationReading extends WeatherphAppModel
{
    public $name = 'WeatherphStationReading';
    public $useTable = false;

    public function find($conditions = null, $fields = array(), $order = null, $recursive = null)
    {
        $date = 'YYYY-MM-DD';
        
        $readingCount = 0;
        $id = $fields['conditions']['id'];
        
        if (key_exists('date', $fields['conditions'])) {
            $date = $fields['conditions']['date'];
        } else {
            $date = date('Ymd', strtotime('-8 hours'));
        }
        
        $time = date('H', strtotime('-8 hours'));
        //echo $time = date('H:i:s', strtotime('- hours'));
            //exit;
        include dirname(__FILE__) . '/auth.php';
        
        $url = 'http://abfrage.meteomedia.ch/abfrage.php?stationidstring='.$id.'&datumstart='.$date.'&datumend='.$date.'&tl=on&td=on&tx=on&tn=on&t5=on&dir=on&ff=on&g1h=on&g3h=on&qff=on&qnh=on&qfe=on&ap=on&www=on&vis=on&n=on&l=on&metarwx=on&cov=on&clcmch=on&clg=on&rr10m=on&rr1h=on&rain3=on&rain6=on&rain12=on&sno=on&new=on&s10=on&sh=on&ss24=on&gl10=on&gl1h=on&gl24=on&stationsreihe=on&output=csv2&ortoutput=wmo6,name&aufruf=auto';
        $this->log($url);
        
        $stations = array();
        $location = $url;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $location);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, "{$karten['username']}:{$karten['password']}");
        curl_setopt($ch, CURLOPT_USERAGENT, "Weather.com.ph Curl Client 1.0");
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); //times out after 10s 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $result = curl_exec($ch);
        
        $headersSpecimen = 'Datum;utc;min;ort1;dir;ff;ff;g3h;g1h;g1h;tl;tl;td;td;t5;t5;tx;tx;tn;tn;qfe;qff;qff;qnh;ap;www;www;metarwx;vis;cov;n;l;clcmch;clcmch;clg;rr10m;rr1h;rr1h;rr24h;sno;sno;new;new;ss24;ss24;sh;sh;s10;gl24;gl24;gl1h;gl1h;gl10;rain3;rain6;rain12;';
        
        $expected = strstr($result, $headersSpecimen);
        if ($expected == '') {
            // The expected string should contain the headerSpecimen
            $error = 'There was an error generating the CSV from '.$url;
            $this->log($error);
            throw new Exception('There was an error generating the CSV');
            return array();
        }
        
        $rows = explode("\n", $result);
        //$numrow=count($rows);
        $this->log(print_r($rows, true));
        $headers = explode(';', $rows[0]);
        //print_r($headers);

        unset($rows[0]);

        $station_map = array();
        foreach ($rows as $row) {
            if (strlen(trim($row)) > 0) {
                $row = explode(';', $row);
                //print_r($row);
                //$station_map[$rows]['dir'];

                $index = array();
                $current = array();
                foreach ($row as $key => $field) {
                    $index = $headers[$key];
                    if (strlen($index) == 0)
                    {
                        // IGNORE empty indexes
                    }
                    else
                    {
                        $current[$index] = $field;
                    }
                }

                $cleanData = true;

                if ($cleanData) {
                    $station_map[] = $current;
                }
            } // Check for empty rows
        }// Loop through each line

        $readings = array();
        if (is_string($conditions) AND $conditions == 'all' AND empty($fields))
        {
            // default behavior.
            $readings = $station_map;
        }
        else if (is_string($conditions) AND $conditions == 'first')
        {
            $readings = reset($station_map);
        }
        else if (is_string($conditions) AND is_array($fields) AND key_exists('conditions', $fields)) 
        {
            
            $readings = $station_map;
        //$readings = $station_map;
        }
        
        $reversedReadings = $readings;
        rsort($reversedReadings);
        
        
        $validRecord = array();
        foreach ($reversedReadings as $reading) {
            //$this->log(print_r($reading, true));
            if (strlen($reading['dir']) > 0) {
                $validRecord = $reading;
                break;
            }
        }
        $this->log(print_r($validRecord, true));
        if (!empty($validRecord)) {
            $this->log('memememe');
            foreach ($readings as $currentReading) {
                //if ($currentReading['utc'] == $time) {
                    return $currentReading;
                //}
            }
        } else {
            $result = $this->find('all', array('conditions' => array(
                'id'   => $id,
                'date' => date('Ymd', strtotime('-20 hours'))
            )));
            $this->log(print_r($result, true));
        }
        
        $this->log('meme');
        
//        die(print_r($readings, true));
//        foreach ($readings as $row) {
//                $rdate = $row['Datum'];
//                $rtime = $row['utc'];
//                
//                if ($rdate == $date AND $rtime == $time)
//                {
//                
//                $stations[] = array(
//                    'Datum' => $rdate,
//                    'utc' => $rtime
//                );
//                }
//                
//                //if $stations[][]
//        }
        curl_close($ch);

        //return $reading;
    }
}