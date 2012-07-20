<?php

class BandwidthTask extends Shell {
    
    function execute(){
        App::import('Bandwidth', 'Weatherph.Bandwidth');
        error_reporting(E_ALL);
        $Bandwidth = new Bandwidth();
         
        
        foreach (range(1, 17) as $day) {
            $date = '2012-07-'.  str_pad($day, '2', '0', STR_PAD_LEFT);
            echo $date;
            $result = file_get_contents($this->getUrl($date));
            if ($result != 'null') {
                $result = json_decode($result, true);

                $counter = 1;
                foreach ($result as $bw) {
                    if ($Bandwidth->find('count', array('conditions' => array('created' => $bw['created_at']))) == 0) {
                        $Bandwidth->create();
                        $Bandwidth->save(array(
                            'created' => $bw['created_at'],
                            'elapsed_time' => $bw['elapsed_time'],
                            'received' => $bw['data_received'],
                            'sent' => $bw['data_sent'],
                        ));
                        $counter++;
                    }
                }
                echo ': '.$counter;
            } else {
                echo ': 0';
            }
            echo "\n";
        }
        
    }
    
    public function getUrl($date) {
        
        $date = date('Y-m-d', strtotime($date));
        $date2 = date('Y-m-d', strtotime('+1 day', strtotime($date)));
        
        $url = "https://desktop.vps.net/json.php?action=bandusage&cloud_id=37&server=81610&cpsid=9d2asqgfdqjo3deh2in6gln2r5&_dc=1342528579716&page=1&start=0&limit=25&filter=%5B%7B%22property%22%3A%22from%22%2C%22value%22%3A%22";
        $url .= $date;
        $url .= 'T00%3A00%3A00%22%7D%2C%7B%22property%22%3A%22to%22%2C%22value%22%3A%22';
        $url .= $date2;
        $url .= 'T00%3A00%3A00%22%7D%5D';
        //echo $url;
        return $url;
    }
}