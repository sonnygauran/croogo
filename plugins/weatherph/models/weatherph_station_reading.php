<?php

include 'weatherph_station_forecast.php';
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
        
        //debug($fields);exit;
        
        $readingCount = 0;
        $id = $fields['conditions']['id'];
        
        if (key_exists('date', $fields['conditions'])) {
            $date = $fields['conditions']['date'];
        } else {
            $date = date('Ymd', strtotime('-2 day -8 hours'));
        }
        
        if (key_exists('datumend', $fields['conditions'])) {
            $datumend = $fields['conditions']['datumend'];
        } else {
            $datumend = date('Ymd');
        }
        
        
        $time = date('H', strtotime('-8 hours'));
        //echo $time = date('H:i:s', strtotime('- hours'));
            //exit;
        include dirname(__FILE__) . '/auth.php';
        
        
        
        //$url = 'http://abfrage.meteomedia.ch/abfrage.php?stationidstring='.$id.'&datumstart='.$date.'&datumend='.$datumend.'&tl=on&td=on&tx=on&tn=on&t5=on&dir=on&ff=on&g1h=on&g3h=on&qff=on&qnh=on&qfe=on&ap=on&www=on&vis=on&n=on&l=on&metarwx=on&cov=on&clcmch=on&clg=on&rr10m=on&rr1h=on&rain3=on&rain6=on&rain12=on&sno=on&new=on&s10=on&sh=on&ss24=on&gl10=on&gl1h=on&gl24=on&stationsreihe=on&output=csv2&ortoutput=wmo6,name&aufruf=auto';
        //$url = 'http://abfrage.meteomedia.ch/manila.php?stationidstring='.$id.'&datumstart='.$date.'&datumend='.$datumend.'&tl=on&td=on&tx=on&tn=on&dir=on&ff=on&g1h=on&g3h=on&qff=on&qnh=on&qfe=on&ap=on&www=on&vis=on&n=on&l=on&metarwx=on&cov=on&clcmch=on&clg=on&rr10m=on&rr1h=on&rain3=on&rain6=on&rain12=on&sno=on&new=on&s10=on&sh=on&ss24=on&gl10=on&gl1h=on&gl24=on&stationsreihe=on&output=csv2&ortoutput=wmo6,name&aufruf=auto';
        
        $url = 'http://abfrage.meteomedia.ch/manila.php?stationidstring='.$id.'&datumstart='.$date.'&datumend='.$datumend.'&dir=on&tl=on&ff=on&td=on&tx=on&tn=on&qff=on&ap=on&www=on&metarwx=on&vis=on&cov=on&n=on&l=on&clcmch=on&clg=on&output=csv2&ortoutput=wmo6,name&aufruf=auto';
        
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
        
        //debug($result);exit;
        
        //$headersSpecimen = 'Datum;utc;min;ort1;dir;ff;ff;g3h;g1h;g1h;tl;tl;td;td;t5;t5;tx;tx;tn;tn;qfe;qff;qff;qnh;ap;www;www;metarwx;vis;cov;n;l;clcmch;clcmch;clg;rr10m;rr1h;rr1h;rr24h;sno;sno;new;new;ss24;ss24;sh;sh;s10;gl24;gl24;gl1h;gl1h;gl10;rain3;rain6;rain12;';
        
        $headersSpecimen = 'Datum;utc;min;ort1;dir;ff;tl;td;tx;tn;qff;ap;www;metarwx;vis;cov;n;l;clcmch;clg;';
        
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
        //$this->log(print_r($rows, true));
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
        
        //debug($readings);exit;
        
        $reversedReadings = $readings;
        rsort($reversedReadings);
        //$this->log($reversedReadings);
        
        
        $validRecord = array();
        foreach ($reversedReadings as $reading) {
            //$this->log(print_r($reading, true));
            if (strlen(trim($reading['dir'])) > 0) {
                if(key_exists('utc', $fields['conditions'])){
                    $validRecord[] = $reading;
                }  else {
                    $validRecord = $reading;
                    break;
                }
                
                
            }
        }
        
        //debug($validRecord);
 
        
        //$this->log(print_r($validRecord, true));
        if (!empty($validRecord)) 
        {
            $this->log('memememe');
            return $validRecord;
//            foreach ($readings as $currentReading)
//            {
//                //if ($currentReading['utc'] == $time) {
//                    return $currentReading;
//            //}
//            }
        } else {
            return array();
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

        return $reading;
    }
    
    public function get($conditions = null, $fields = array(), $order = null, $recursive = null)
    {
        error_reporting(0);
        
        App::import('Model', 'Weatherph.WeatherphStation');
        
        $WeatherphStation = new WeatherphStation();
        $stations = $WeatherphStation->find('all', array('conditions' => array(
            'provider' => 'pagasa',
        )));
       
        //debug($stations);exit;

        $date = 'YYYY-MM-DD';
        
        include dirname(__FILE__) . '/auth.php';
        
        ini_set('memory_limit', '256M');
        
        $WeatherphStationReading = new WeatherphStationReading();
        
        $stationsReadings = array();
        
        foreach($stations as $station){
            
            $datumstart = $datumend = date('Ymd',strtotime($fields['conditions']['date']));
            
            $currentReadings = $WeatherphStationReading->find('all', array('conditions' => array(
                    'id' => $station['id'],
                    'date' => $datumstart,
                    'datumend' => $datumend,
                    'utc' => 'all',
            )));
            
            foreach($currentReadings as $currentReading){
                   
                $stationsReadings
                    [$currentReading['ort1']]
                        [$currentReading['Datum']]
                            [$currentReading['utc']]= $currentReading;

            }
            
            
            //debug($stationsReadings);exit;
            
        }
        
        //debug($stationsReadings);exit;
        
        return $stationsReadings;

        
    }
    
    
    
public function arrayToAnyChartXML ($conditions = null, $fields = array(), $order = null, $recursive = null){
        
        $arrData = $fields['conditions']['arrData'];
        $type = strtolower($fields['conditions']['type']);
        
        switch($type){
            
            case 'temperature':
            case 'temp':
                $min_interval = 6;
                $show_label_cross = 'False';
                $default_series_type = 'Spline';
                
            break;
            case 'wind':
                $min_interval = 3;
                $show_label_cross = 'True';
                $default_series_type = 'Spline';
                
            break;
            case 'winddir':
                $min_interval = 3;
                $show_label_cross = 'True';
                $default_series_type = 'Line';
                
            break;
            case 'humidity':
                $min_interval = 3;
                $show_label_cross = 'False';
                $default_series_type = 'Spline';
                
            break;
            case 'precipitation':
            case 'precip':
                $min_interval = 3;
                $show_label_cross = 'True';
                $default_series_type = 'Bar';
            break;
            case 'airpressure':
                $min_interval = 3;
                $show_label_cross = 'False';
                $default_series_type = 'Spline';
                
            break;
            case 'globalradiation':
                $min_interval = 3;
                $show_label_cross = 'True';
                $default_series_type = 'Bar';
                
            break;
            
        }
        
        $xml_string = 
            '<?xml version="1.0" encoding="ISO-8859-1"?>
                <anychart>
                <margin all="3" bottom="0" left="10" right="8"/>
                <settings>
                    <locale>
                        <date_time_format>
                            <format>%u</format>';
        
        if($type == 'precipitation' || $type == 'precip'){
        $xml_string .= '<months>
                            <names>
                                January,February,March,April,May,June,July,August,September,October,November,December
                            </names>
                            <short_names>Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sep,Oct,Nov,Dec</short_names>
                        </months>';
        }
        
        $xml_string .= '<week_days start_from="Sunday">
                                <short_names>
                                    <![CDATA[ Su.,Mo.,Tu.,We.,Th.,Fr.,Sa. ]]>
                                </short_names>
                            </week_days>
                        </date_time_format>
                    </locale>
                </settings>
                <charts>
                    <chart plot_type="Scatter">
                        <chart_settings>
                            <title enabled="false"/>
                            <axes>
                                <x_axis>
                                    <scale type="DateTime" minimum_offset="0" maximum_offset="0" minor_interval="'.$min_interval.'" minor_interval_unit="Hour" major_interval="1" major_interval_unit="Day"/>
                                    <title enabled="false"/>
                                    <labels enabled="True" show_cross_label="'.$show_label_cross.'" allow_overlap="true">
                                        <format>
                                            <![CDATA[ ]]>
                                            {%Value}{dateTimeFormat:%ddd %dd.%MM.}
                                        </format>
                                        <font family="Arial" color="#444444" size="11"/>
                                    </labels>
                                    <major_grid enabled="True" interlaced="True">
                                        <interlaced_fills>
                                            <even>
                                                <fill color="rgb(245,245,245)" opacity="1"/>
                                            </even>
                                        </interlaced_fills>
                                    </major_grid>
                                </x_axis>';
        
        
        //if($type != 'precipitation' && $type != 'precip'){
        $xml_string .=  '       <y_axis>
                                    <!--scale type="Linear" maximum="auto" minimum="auto" maximum_offset="0.01" minimum_offset="0.01"/-->
                                    <title enabled="false"/>
                                    <labels>
                                        <format>{%Value}{numDecimals:0}</format>
                                        <font family="Arial" color="#444444" size="11"/>
                                    </labels>
                                </y_axis>';
        //}
        
        $xml_string .= '
                                <extra>
                                    <y_axis name="y2" enabled="False">
                                        <title enabled="false"/>
                                        <labels>
                                            <format/>
                                        </labels>
                                    </y_axis>
                                </extra>
                            </axes>
                        <chart_background enabled="false">
                            <effects enabled="false"/>
                            <border enabled="false"/>
                            <inside_margin all="0"/>
                        </chart_background>
                        <data_plot_background enabled="false">
                            <effects enabled="false"/>
                        </data_plot_background>
                    </chart_settings>';
        
        $xml_string .= '
                <data_plot_settings default_series_type="'.$default_series_type.'">
                    <line_series>
                        <marker_settings enabled="false"/>
                        <line_style>
                            <line enabled="true" thickness="2" caps="round" joints="round"/>
                        </line_style>';
        
        if($type == 'temp' || $type == 'temperature'){
        $xml_string .= '<tooltip_settings enabled="true">
                            <format>
                                <![CDATA[ {%YValue}{numDecimals:1} ]]>
                            </format>
                        </tooltip_settings>';
        }
        
        $xml_string .= '
                    </line_series>
                </data_plot_settings>
                <styles>';
        
        if($type == 'temperature' || $type == 'temp'){
        $xml_string .= '
                    <line_style name="tlline" color="#c80000">
                        <line thickness="2"/>
                    </line_style>
                    <line_style name="tdline" color="#00c800">
                        <line thickness="2"/>
                    </line_style>
                    <line_style name="noline">
                        <line enabled="False"/>
                    </line_style>
                    <marker_style name="dotblue" color="blue">
                        <marker size="3" type="circle"/>
                    </marker_style>
                    <marker_style name="dotred" color="#c80000">
                        <marker size="3" type="circle"/>
                    </marker_style>';
        }elseif($type == 'wind'){
            
        $xml_string .= '
                    <line_style name="ffline" color="#966400"/>
                    <line_style name="fgline" color="#c800aa"/>';    
            
        }elseif($type == 'winddir'){
            
        $xml_string .= '
                    <line_style name="dirline" color="green">
                        <line enabled="false"/>
                    </line_style>
                    <marker_style name="south">
                        <marker type="Image" image_url="imgs/wind_south.png" size="23"/>
                    </marker_style>
                    <marker_style name="south_west">
                        <marker type="Image" image_url="imgs/wind_south_west.png" size="23"/>
                    </marker_style>
                    <marker_style name="south_east">
                        <marker type="Image" image_url="imgs/wind_south_east.png" size="23"/>
                    </marker_style>
                    <marker_style name="west">
                        <marker type="Image" image_url="imgs/wind_west.png" size="23"/>
                    </marker_style>
                    <marker_style name="east">
                        <marker type="Image" image_url="imgs/wind_east.png" size="23"/>
                    </marker_style>
                    <marker_style name="north">
                        <marker type="Image" image_url="imgs/wind_north.png" size="23"/>
                    </marker_style>
                    <marker_style name="north_west">
                        <marker type="Image" image_url="imgs/wind_north_west.png" size="23"/>
                    </marker_style>
                    <marker_style name="north_east">
                        <marker type="Image" image_url="imgs/wind_north_east.png" size="23"/>
                    </marker_style>';
            
        }elseif($type == 'humidity'){
            
        $xml_string .= '<line_style name="rhline" color="#00c800"/>';
            
        }
        
        $xml_string .= '
                </styles>
                <data>';
        
        if($type == 'temperature' || $type == 'temp'){
        // Temperture
        
        $xml_string .= '
                    <series name="80b" style="tlline" use_hand_cursor="False" hoverable="False">
                        <tooltip enabled="false"/>';
        
        foreach($arrData as $data){
            $xml_string .= '<point name="'.$data['utcDate'].'" x="'.$data['utcDate'].'" y="'.$data['tl'].'"/><!-- '.date('Y-m-d H:i:s', $data['utcDate']).'-->';
        }
        
        $xml_string .='</series>';
                    
        $xml_string .='<series name="80c" style="tdline" use_hand_cursor="False" hoverable="False">
                    <tooltip enabled="false"/>';
        
        foreach($arrData as $data){
            $xml_string .= '<point name="'.$data['utcDate'].'" x="'.$data['utcDate'].'" y="'.$data['td'].'"/><!-- '.date('Y-m-d H:i:s', $data['utcDate']).'-->';
        }
        
        $xml_string .='</series>';
        
        }elseif($type == 'wind'){
            
            $xml_string .= '
                    <series name="80b" style="ffline" use_hand_cursor="False" hoverable="False">
                        <tooltip enabled="false"/>';
        
            foreach($arrData as $data){
                $xml_string .= '<point name="'.$data['utcDate'].'" x="'.$data['utcDate'].'" y="'.$data['ff'].'"/><!-- '.date('Y-m-d H:i:s', $data['utcDate']).'-->';
            }

            $xml_string .='</series>';
            
            $xml_string .= '
                    <series name="80b" style="fgline" use_hand_cursor="False" hoverable="False">
                        <tooltip enabled="false"/>';
        
            foreach($arrData as $data){
                $xml_string .= '<point name="'.$data['utcDate'].'" x="'.$data['utcDate'].'" y="'.$data['fg'].'"/><!-- '.date('Y-m-d H:i:s', $data['utcDate']).'-->';
            }

            $xml_string .='</series>';
            
        }elseif($type == 'humidity'){
            
            $xml_string .= '
                    <series name="80b" style="rhline" use_hand_cursor="False" hoverable="False">
                        <tooltip enabled="false"/>';
        
            foreach($arrData as $data){
                $xml_string .= '<point name="'.$data['utcDate'].'" x="'.$data['utcDate'].'" y="'.$data['rh'].'"/><!-- '.date('Y-m-d H:i:s', $data['utcDate']).'-->';
            }

            $xml_string .='</series>';
            
        }elseif($type == 'precip' || $type == 'precipitation'){
            
            $xml_string .= '
                    <series name="80b" use_hand_cursor="False" hoverable="False">';
        
            foreach($arrData as $data){
                $xml_string .= '<point name="'.$data['utcDate'].'" x="'.$data['utcDate'].'" y="'.$data['rr'].'"/><!-- '.date('Y-m-d H:i:s', $data['utcDate']).'-->';
            }

            $xml_string .='</series>';
            
        }elseif($type == 'airpressure'){
            
            $xml_string .= '
                    <series name="80b" use_hand_cursor="False" hoverable="False">';
        
            foreach($arrData as $data){
                $xml_string .= '<point name="'.$data['utcDate'].'" x="'.$data['utcDate'].'" y="'.$data['qff'].'"/><!-- '.date('Y-m-d H:i:s', $data['utcDate']).'-->';
            }

            $xml_string .='</series>';
            
        }elseif($type == 'globalradiation'){
            
            $xml_string .= '
                    <series name="80b" use_hand_cursor="False" hoverable="False">';
        
            foreach($arrData as $data){
                $xml_string .= '<point name="'.$data['utcDate'].'" x="'.$data['utcDate'].'" y="'.$data['gl1h'].'"/><!-- '.date('Y-m-d H:i:s', $data['utcDate']).'-->';
            }

            $xml_string .='</series>'; 
            
        }
        
        
        $xml_string .= '
                </data>
                </chart>
                </charts>
            </anychart>';
        
            $xml = simplexml_load_string($xml_string);
            
            return $xml;
        
    }
    
    public function getDetailedReading($conditions = null, $fields = array(), $order = null, $recursive = null){
        
        include dirname(__FILE__) . '/auth.php';
        
        //$this->log(print_r($fields['conditions']));
        
        $stationId = $fields['conditions']['id'];
        $type = $fields['conditions']['type'];
        $timeRes = ($fields['conditions']['timeRes'] == NULL )? '1h' : $fields['conditions']['timeRes'];
        
        $startdatum = $fields['conditions']['startDatum'];
        $startdatum = ($startdatum == NULL)? date('Ymd') : date('Ymd',strtotime($startdatum));
        
        //$this->log($fields);
        $enddatum = $fields['conditions']['endDatum'];
        
        //$this->log($enddatum);
        
        // Input validation
        $enddatum = ($enddatum == NULL)? date('Ymd') : date('Ymd', strtotime($enddatum)); 
        
        
        //Grab stations readings  
        $url = "http://192.168.20.89/abfrage.php?stationidstring=$stationId&datumstart=$startdatum&datumend=$enddatum&zeiten1=$timeRes&mosmess=ja&paramliste=dir,ff,g3h,tl,td,qff,rr,sh,gl1h,rh&output=csv2&ortoutput=wmo6,name&aufruf=auto";
        
        $this->log($url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, "{$karten['username']}:{$karten['password']}");
        curl_setopt($ch, CURLOPT_USERAGENT, "Weather.com.ph Curl Client 1.0");
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); //times out after 10s ,
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $curlResults = curl_exec($ch);
        curl_close($ch);
        
        //$this->log($curlResults);
        
        $headersSpecimen = 'Datum;utc;min;ort1;dir;ff;g3h;tl;td;qff;rr;sh;gl1h;rh;';
        
        $results = $this->csvToArray($curlResults, $headersSpecimen);
        
        foreach($results as $result){
            
            if(trim($result['tl'])!=''){
         
               //explode the ort1 raw data, grab only those needed
                $result['ort1'] = explode('/', $result['ort1']);
                unset($result['ort1'][0]);
                $result['ort1'] = implode('/', $result['ort1']);
                
                $abfrageResults['ort1'] = $result['ort1']; 
                $abfrageResults['forecast'][$result['Datum']][] = $result;

            }
       
        }
        
        //debug($abfrageResults['forecast']);exit;
        
            $resultData = array();
            foreach($abfrageResults['forecast'] as $key=>$forecast){
                foreach($forecast as $data){
                    
                    if($type == 'temp' || $type == 'temperature'){
                        
                        $resultData[] = array(
                            'utcDate' => strtotime($data['Datum'] . ' ' . $data['utc'] . ':' . $data['min']),
                            'tl' => round($data['tl']),
                            'td' => round($data['td']),
                            );
                        
                    }elseif($type == 'wind'){
            
                        $resultData[] = array(
                            'utcDate' => strtotime($data['Datum'] . ' ' . $data['utc'] . ':' . $data['min']),
                            'ff' => round($data['ff']),
                            'fg' => round($data['g3h']),
                            );
            
                    }elseif($type == 'humidity'){
                        
                        $resultData[] = array(
                            'utcDate' => strtotime($data['Datum'] . ' ' . $data['utc'] . ':' . $data['min']),
                            'rh' => $data['rh'],
                            );

                    }elseif($type == 'winddir'){
                        
                        $resultData[] = array(
                            'utcDate' => strtotime($data['Datum'] . ' ' . $data['utc'] . ':' . $data['min']),
                            'dir' => $data['dir'],
                            );

                    }elseif($type == 'precipitation' || $type == 'precip'){
                        
                        $resultData[] = array(
                            'utcDate' => strtotime($data['Datum'] . ' ' . $data['utc'] . ':' . $data['min']),
                            'rr' => round($data['rr']),
                            );

                    }elseif($type == 'airpressure'){
                        
                        $resultData[] = array(
                            'utcDate' => strtotime($data['Datum'] . ' ' . $data['utc'] . ':' . $data['min']),
                            'qff' => round($data['qff']),
                            );

                    }elseif($type == 'globalradiation'){
                        
                        $resultData[] = array(
                            'utcDate' => strtotime($data['Datum'] . ' ' . $data['utc'] . ':' . $data['min']),
                            'gl1h' => round($data['gl1h']),
                            );
                        
                    }
                    
                }

            }
            
        //debug($resultData);exit;
        
        $abfrageResults = ($type != NULL)?  $resultData : $abfrageResults;     
        
        return $abfrageResults;
       
        
    }
    
    
    public function csvToArray($csv, $headersSpecimen){
        
        //Convert 
        $expected = strstr($csv, $headersSpecimen);
        
        //$this->log(print_r($csv));
        
        
        if ($expected == '') {
            $error = 'There was an error generating the CSV from'.$url;
            $this->log($error);
            throw new Exception('There was an error generating the CSV');
            return array();
        }

        $rows = explode("\n", $csv);
        $headers = explode(';', $rows[0]);
        
        unset($rows[0]);

        $arrayResults = array();
        foreach($rows as $key=>$row){
            if(trim($row) != ''){  
            $params = explode(';' , $row);
            foreach($params as $key2=>$param){
                    if($headers[$key2]!=''){
                        $arrayResults[$key]
                            [$headers[$key2]] = trim($param);
                    }
                } 
            }

        }
            
        return $arrayResults;
        
        
    }
    
    public function getStationInfo($stationID = NULL, $keys = NULL){
        
        if($stationID == NULL){
            
            $error = 'Station ID is required'.$url;
            $this->log($error);
            throw new Exception('Station ID is required');
            return NULL;
            
        }else{
             
            include dirname(__FILE__) . '/auth.php';

            $url = "http://192.168.20.89/abfrage.php?stationidstring=$stationID&ortsinfo=ja&paramtyp=mos_mix_mm&output=html&aufruf=auto";

            $this->log($url);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERPWD, "{$karten['username']}:{$karten['password']}");
            curl_setopt($ch, CURLOPT_USERAGENT, "Weather.com.ph Curl Client 1.0");
            curl_setopt($ch, CURLOPT_TIMEOUT, 10); //times out after 10s 
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

            $curlResults = curl_exec($ch);
            curl_close($ch);
            
            $headersSpecimen = "id;name;typ;locator;wmo;reg;wmo1;wmo2;wmo3;icao;iat;aktiv;mos_gfs_kn;mos_ez_kn;mos_ez_mm;mos_preferred;lat;lon;alt;altp;xxx;spezial;org;land;bundesland;von;bis;wmoalt;wmoaltbis;metrilognummer;stationstyp;webname;webaktiv;dlat;dlon;ersatzstation;zeitzone;olsontimezone;webnosponsor;";
        
            $results = $this->csvToArray($curlResults, $headersSpecimen);
            
            if(count($results) > 1) unset($results[2]);
            
        }
        
        $results = $results[1];
        
        if($keys == NULL){
            return $results;
        }else{
            if(is_array($keys)){
                
                $new_results = array();
                
                foreach($keys as $key){
                    $new_results[$key] = $results[$key];
                }
                
                return $new_results;
                
            }else{
                return $results[$keys];
            }
            
        }
        
    }
    
}

