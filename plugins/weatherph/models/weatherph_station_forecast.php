<?php

/**
 * Acquires the of Weather Forecast Stations
 */
class WeatherphStationForecast extends WeatherphAppModel
{
    
    public $name = 'WeatherphStationForecast';
    public $useTable = false;
    
    public function get($conditions = null, $fields = array(), $order = null, $recursive = null){
        
        include dirname(__FILE__) . '/auth.php';
        
        date_default_timezone_set('Asia/Manila');
        
        
        $daysStr = ($fields['conditions']['target_days'] > 1)? 'days' : 'day';
        $fields['conditions']['target_days'] = $fields['conditions']['target_days'] - 1;
        
        $stationId = $fields['conditions']['id'];
 
        $startdatum = date('Ymd');
        
        $enddatum = strtotime("+" . $fields['conditions']['target_days'] . $daysStr, strtotime($startdatum));
        $enddatum = date('Ymd', $enddatum);
        
        $utch = $fields['conditions']['utch'];
        
        $startutc = "00";
        $endutc = "00";
        
        $abfrageResults = array();
        
        $headersSpecimen = "Datum;utc;min;ort1;dir;ff;g3h;tl;rr;sy;rh;sy2;";
        
        $stationInfo = $this->getStationInfo($stationId, array("lat","lon"));
        
        //Grab stations readings  
        $url = "http://192.168.20.89/abfrage.php?stationidstring=$stationId&datumstart=$startdatum&datumend=$enddatum&&zeiten1=10m&tl=on&dir=on&ff=on&g3h=on&paramliste=rr,rh,sy,sy2&output=csv2&ortoutput=wmo6,name&aufruf=auto";
        
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
        
//        debug($curlResults);exit;
        
        $resultsReadings = $this->csvToArray($curlResults, $headersSpecimen);
        
        $currentReadings = array();
        foreach($resultsReadings as $readings){
            if($readings['tl']!=''){
                
                //Determine sunrise and sunset for every location using latituted and longtitude
                $sunrise = date_sunrise(strtotime($readings['Datum']), SUNFUNCS_RET_STRING, $stationInfo['lat'], $stationInfo['lon'], 90);
                $sunset = date_sunset(strtotime($readings['Datum']), SUNFUNCS_RET_STRING, $stationInfo['lat'], $stationInfo['lon'], 90);

                $readings['sunrise'] = $sunrise;
                $readings['sunset'] = $sunset;
                
                $readings['ort1'] = explode('/', $readings['ort1']);
                unset($readings['ort1'][0]);
                $readings['ort1'] = implode('/', $readings['ort1']);
                
                //Determine weather symbol for certain utc time
                $readings['sy'] = $this->dayOrNightSymbol(number_format($readings['sy'],0), $readings['utc'], array("sunrise"=>$sunrise,"sunset"=>$sunset));
                
                // Replace the null values with hypen character and round it off to the nearest tenths
                $readings['tl'] = ($readings['tl'] == '')? '0' : round($readings['tl'],0);
                $readings['rr'] = ($readings['rr'] == '')? '0' : round($readings['rr'],0);
                $readings['rh'] = ($readings['rh'] == '')? '0' : round($readings['rh'],0);
                $readings['ff'] = ($readings['ff'] == '')? '0' : round($readings['ff'],0);
                $readings['g3h'] = ($readings['g3h'] == '')? '0' : round($readings['g3h'],0);
                
                // Translate raw data to wind direction image value
                $readings['dir'] = $this->showWindDirection($readings['dir']);
                
                $thierTime = strtotime($readings['Datum'].' '.$readings['utc'].':'.$readings['min']);
                $ourTime = strtotime('+8 hours', $thierTime);
                $readings['update'] = date('h:iA', $ourTime);
                
                $currentReadings[] = $readings;
                
            }
        }
        
        $currentReading = array_pop($currentReadings);
        $abfrageResults['reading'] = $currentReading;
        
        //$this->log(print_r($currentReadings, true));
        
        //Grab stations forecast  
        $url = "http://192.168.20.89/abfrage.php?stationidstring=$stationId&datumstart=$startdatum&datumend=$enddatum&&zeiten1=$utch&paramtyp=mos_mix_mm&mosmess=ja&tl=on&dir=on&ff=on&g3h=on&paramliste=rr,rh,sy,sy2&output=csv2&ortoutput=wmo6,name&aufruf=auto";
        
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
        
        $resultsForecast = $this->csvToArray($curlResults, $headersSpecimen);
        
        $nowHour = date('H',strtotime($currentReading['update']));
        $nowHourRound = $nowHour - ($nowHour % 3);
        
        $hourStart = false;
        
        foreach($resultsForecast as $result){
            
            if(trim($result['tl'])!=''){
                
                //Determine sunrise and sunset for every location using latituted and longtitude
                $sunrise = date_sunrise(strtotime($result['Datum']), SUNFUNCS_RET_STRING, $stationInfo['lat'], $stationInfo['lon'], 90);
                $sunset = date_sunset(strtotime($result['Datum']), SUNFUNCS_RET_STRING, $stationInfo['lat'], $stationInfo['lon'], 90);

                $result['sunrise'] = $sunrise;
                $result['sunset'] = $sunset;

                //explode the ort1 raw data, grab only those needed
                $result['ort1'] = explode('/', $result['ort1']);
                unset($result['ort1'][0]);
                $result['ort1'] = implode('/', $result['ort1']);
                
                $abfrageResults['ort1'] = $result['ort1'];
                
                //Determine weather symbol for certain utc time
                $result['sy'] = $this->dayOrNightSymbol(number_format($result['sy'],0), $result['utc'], array("sunrise"=>$sunrise,"sunset"=>$sunset));
                
                // Replace the null values with hypen character and round it off to the nearest tenths
                $result['tl'] = ($result['tl'] == '')? '0' : round($result['tl'],0);
                $result['rr'] = ($result['rr'] == '')? '0' : round($result['rr'],0);
                $result['rh'] = ($result['rh'] == '')? '0' : round($result['rh'],0);
                $result['ff'] = ($result['ff'] == '')? '0' : round($result['ff'],0);
                $result['g3h'] = ($result['g3h'] == '')? '0' : round($result['g3h'],0);
                
                // Translate raw date to 3 hourly range value
                //$result['utch'] = $result['utc'] . ':' . $result['min'] .' - '. sprintf('%02d',$result['utc'] + 3) .':'. $result['min'];
                $thierTime = strtotime($result['Datum'].' '.$result['utc'].':'.$result['min']);
                $ourTime = strtotime('+8 hours', $thierTime);
                $result['utch'] = date('H:iA', $ourTime);
                $result['outtime'] = $nowHourRound;
                
                // Translate raw data to wind direction image value
                $result['dir'] = $this->showWindDirection($result['dir']);
                
                unset($result['ort1']);
                
//                if (!key_exists('reading', $abfrageResults) AND !$hourStart) {
//                    if ($result['utc'] == $nowHourRound) {
//                        $abfrageResults['reading'] = $result;
//                    }
//                } else {
//                    $abfrageResults['forecast'][] = $result;
//                }
                
                
                //if (date('H', strtotime($result['Datum'] .' '. $result['utch'])) >= $nowHourRound) {
                    $abfrageResults['forecast'][] = $result;
                //}
            }
        }
        
        $this->log(print_r($abfrageResults, true));
       
        return $abfrageResults;
        
    }
    
    public function getWeeklyForecast($conditions = null, $fields = array(), $order = null, $recursive = null){
        
        include dirname(__FILE__) . '/auth.php';
        
        date_default_timezone_set('Asia/Manila');
        
        $stationId = $fields['conditions']['id'];
 
        $startdatum = date('Ymd');
        $enddatum = strtotime("+4 days", strtotime($startdatum));
        $enddatum = date('Ymd', $enddatum);
        
        //Grab stations readings  
        $url = "http://192.168.20.89/abfrage.php?stationidstring=$stationId&datumstart=$startdatum&datumend=$enddatum&&zeiten1=3h&paramtyp=mos_mix_mm&mosmess=ja&tl=on&dir=on&ff=on&g3h=on&paramliste=rr,rh,sy,sy2&output=csv2&ortoutput=wmo6,name&timefill=nein&verknuepft=nein&aufruf=auto";
        
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
        
        $stationInfo = $this->getStationInfo($stationId, array("lat","lon"));
             
        $headersSpecimen = "Datum;utc;min;ort1;dir;ff;g3h;tl;rr;sy;rh;sy2;";
        
        $results = $this->csvToArray($curlResults, $headersSpecimen);
        
        $nowHour = date('H');
        $nowHourRound = $nowHour - ($nowHour % 3);
        
        $hourStart = false;
        $abfrageResults = array();
        foreach($results as $result){
            
            if(trim($result['tl'])!=''){
                
                //Determine sunrise and sunset for every location using latituted and longtitude
                $sunrise = date_sunrise(strtotime($result['Datum']), SUNFUNCS_RET_STRING, $stationInfo['lat'], $stationInfo['lon'], 90);
                $sunset = date_sunset(strtotime($result['Datum']), SUNFUNCS_RET_STRING, $stationInfo['lat'], $stationInfo['lon'], 90);

                
                $result['sunrise'] = $sunrise;
                $result['sunset'] = $sunset;

                //explode the ort1 raw data, grab only those needed
                $result['ort1'] = explode('/', $result['ort1']);
                unset($result['ort1'][0]);
                $result['ort1'] = implode('/', $result['ort1']);
                
                $abfrageResults['stationId'] = $stationId; 
                $abfrageResults['ort1'] = $result['ort1'];
                $abfrageResults['update'] = date('H:iA');
                
                $thierTime = strtotime($result['Datum'].' '.$result['utc']);
                $ourTime = strtotime('+8 hours', $thierTime);
                $abfrageResults['update'] = date('H:iA', $ourTime);
                
                //Determine weather symbol for certain utc time
                $result['sy'] = $this->dayOrNightSymbol(number_format($result['sy'],0), $result['utc'], array("sunrise"=>$sunrise,"sunset"=>$sunset));
                
                // Replace the null values with hypen character and round it off to the nearest tenths
                $result['tl'] = ($result['tl'] == '')? '0' : round($result['tl'],0);
                $result['rr'] = ($result['rr'] == '')? '0' : round($result['rr'],0);
                $result['rh'] = ($result['rh'] == '')? '0' : round($result['rh'],0);
                $result['ff'] = ($result['ff'] == '')? '0' : round($result['ff'],0);
                $result['g3h'] = ($result['g3h'] == '')? '0' : round($result['g3h'],0);
                
                $result['moonphase'] = $this->moon_phase(date('Y', strtotime($result['Datum'])), date('m', strtotime($result['Datum'])), date('d', strtotime($result['Datum'])));
                
                // Translate raw date to 3 hourly range value
                $result['utch'] = date('H:sA', strtotime($result['Datum'] .' '. $result['utc'] . ':' . $result['min']));
                
                // Translate raw data to wind direction image value
                $result['dir'] = $this->showWindDirection($result['dir']);
                
                unset($result['ort1']);
                
                if (!key_exists('reading', $abfrageResults) AND !$hourStart) {
                    if ($result['utc'] == $nowHourRound) {
                        $abfrageResults['reading'] = $result;
                    }
                }
                
                $abfrageResults['forecast'][$result['Datum']][] = $result;
                
            }
        }
       
        return $abfrageResults;
        
    }
    
    public function getDetailedForecast($conditions = null, $fields = array(), $order = null, $recursive = null){
        
        include dirname(__FILE__) . '/auth.php';
        
        $stationId = $fields['conditions']['id'];
        $type = $fields['conditions']['type'];
        $timeRes = ($fields['conditions']['timeRes'] == NULL )? '1h' : $fields['conditions']['timeRes'];
        $startdatum = ($fields['conditions']['startDatum'] == NULL)? date('Ymd') : date('Ymd',strtotime($startdatum));
        
        $enddatum = strtotime('+4 days', strtotime($startdatum));
        $enddatum = date('Ymd', $enddatum);
        
        //Grab stations readings  
        $url = "http://192.168.20.89/abfrage.php?stationidstring=$stationId&datumstart=$startdatum&datumend=$enddatum&&zeiten1=$timeRes&paramtyp=mos_mix_mm&mosmess=ja&paramliste=tl,td,rh,ff,g3h,dir,qff,sh,gl1h,rr&output=csv2&ortoutput=wmo6,name&timefill=nein&verknuepft=nein&aufruf=auto";
        
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
    
    public function csvToArray($csv, $headersSpecimen){
        
        //Convert 
        $expected = strstr($csv, $headersSpecimen);

        if ($expected == '') {
            $error = 'There was an error generating the CSV from '.$url;
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
    
    private function dayOrNightSymbol($symbol = NULL, $utc = NULL, $meridiem = array()){
        
        if($symbol == NULL || trim($symbol) == ''){
   
              return NULL;
              
        }else{
            
            $utc = (int)$utc + 3;
            
            $sunrise = date('H', strtotime($meridiem['sunrise']));
            $sunset = date('H', strtotime($meridiem['sunset']));
            
            if($utc > $sunrise && $utc < $sunset){
                $dayOrNight = 'day';
            }else{
                $dayOrNight = 'night';
            }
            
            return $dayOrNight . '_' . $symbol;
            
        }
        
    }
    
    private function showWindDirection($wd = NULL) {
        
        if($wd == NULL || trim($wd) == ''){
            
            return NULL;
            
        }else{

            $lowest_value = 1000;

            if ($wd == (-99) || $wd == (-999)) {
                $value = "-99";
            } else {

                if ($wd == 999) {
                    $value = 9;
                } else {

                    $wd_loc = $wd;

                    # 360
                    $diff = abs($wd_loc - 360);
                    if ($diff < $lowest_value) {
                        $lowest_value = $diff;
                        $value = 1;
                    }

                    # 45
                    $diff = abs($wd_loc - 45);
                    if ($diff < $lowest_value) {
                        $lowest_value = $diff;
                        $value = 8;
                    }

                    # 90
                    $diff = abs($wd_loc - 90);
                    if ($diff < $lowest_value) {
                        $lowest_value = $diff;
                        $value = 7;
                    }

                    # 135
                    $diff = abs($wd_loc - 135);
                    if ($diff < $lowest_value) {
                        $lowest_value = $diff;
                        $value = 6;
                    }

                    # 180
                    $diff = abs($wd_loc - 180);
                    if ($diff < $lowest_value) {
                        $lowest_value = $diff;
                        $value = 5;
                    }

                    # 225
                    $diff = abs($wd_loc - 225);
                    if ($diff < $lowest_value) {
                        $lowest_value = $diff;
                        $value = 4;
                    }

                    # 270
                    $diff = abs($wd_loc - 270);
                    if ($diff < $lowest_value) {
                        $lowest_value = $diff;
                        $value = 3;
                    }

                    # 315
                    $diff = abs($wd_loc - 315);
                    if ($diff < $lowest_value) {
                        $lowest_value = $diff;
                        $value = 2;
                    }
                }
            }

            return "wind_" . $value;
        }
    }
    
    private function moon_phase($year, $month, $day){

	$c = $e = $jd = $phase = $b = 0;

	if ($month < 3){
            $year--;
            $month += 12;
        }

	++$month;

	$c = 365.25 * $year;
        $e = 30.6 * $month;
        $jd = $c + $e + $day - 694039.09;   //jd is total days elapsed
        $jd /= 29.5305882;                  //divide by the moon cycle
        $b = (int) $jd;                     //int(jd) -> b, take integer part of jd
        $jd -= $b;                          //subtract integer part to leave fractional part of original jd
        $phase = round($jd * 8);            //scale fraction from 0-8 and round

        if ($phase >= 8 ) $phase = 0;//0 and 8 are the same so turn 8 into 0
        
        switch($phase){
            case 0:
                $moonphase = array("phase"=>"New"); //New Moon
                break;
            case 1:
                $moonphase = array("phase"=>"Waxing"); //Waxing Cresent Moon
                break;
            case 2:
                $moonphase = array("phase"=>"Quarter"); //Queater Moon
                break;
            case 3:
                $moonphase = array("phase"=>"Waxing"); //Waxing Gibbous Moon
                break;
            case 4:
                $moonphase = array("phase"=>"Full"); //Waxing Gibbous Moon
                break;
            case 5:
                $moonphase = array("phase"=>"Waning"); //Waning Gibbous Moon
                break;
            case 6:
                $moonphase = array("phase"=>"Last"); //Last Quarter Moon
                break;
            case 7:
                $moonphase = array("phase"=>"Waning"); //Waning Cresent Moon
                break;
            default:
                $moonphase = array("phase"=>"Error");
                break;
                  
        }
        
        $moonphase['phase_code'] = $phase;
        
	return $moonphase;

    }
    
}