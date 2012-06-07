<?php

class WeatherphAppModel extends AppModel {
        
    protected function generateDate($type, $time_resolution = '1h', $hasUTC = true){

        // Adjust our time so that the data we get can match theirs
        $theirTime = strtotime(($type=='forecast') ? '-16 hours' : '-8 hours', strtotime(date('Ymd'))); 
        $format = array();
        
        $format['start_date'] = date('Ymd', $theirTime);
        $format['time_resolution'] = $time_resolution;
        
        switch($type){
            case 'reading':
                $format['end_date'] = date('Ymd', strtotime('+2 days', $theirTime));
                break;
            case 'forecast':
                $format['end_date'] = date('Ymd', strtotime("+5 Days", $theirTime));;
                break;
        }

        if($hasUTC){
            $format['start_hour'] = date('H', $theirTime);
            $format['end_hour'] = ($type == 'forecast') ? '16' : '00';
        }
        return $format;
    }

    // Show what the moon phase for a certain date.
    protected function moon_phase($year, $month, $day) {

        $c = $e = $jd = $phase = $b = 0;

        if ($month < 3) {
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

        if ($phase >= 8)
            $phase = 0; //0 and 8 are the same so turn 8 into 0

        switch ($phase) {
            case 0:
                $moonphase = array("phase" => "New"); //New Moon
                break;
            case 1:
                $moonphase = array("phase" => "Waxing"); //Waxing Cresent Moon
                break;
            case 2:
                $moonphase = array("phase" => "Quarter"); //Queater Moon
                break;
            case 3:
                $moonphase = array("phase" => "Waxing"); //Waxing Gibbous Moon
                break;
            case 4:
                $moonphase = array("phase" => "Full"); //Waxing Gibbous Moon
                break;
            case 5:
                $moonphase = array("phase" => "Waning"); //Waning Gibbous Moon
                break;
            case 6:
                $moonphase = array("phase" => "Last"); //Last Quarter Moon
                break;
            case 7:
                $moonphase = array("phase" => "Waning"); //Waning Cresent Moon
                break;
            default:
                $moonphase = array("phase" => "Error");
                break;
        }

        $moonphase['phase_code'] = $phase;

        return $moonphase;
    }

    protected function dayOrNightSymbol($symbol = NULL, $utc = NULL, $meridiem = array()) {

        if ($symbol == NULL || trim($symbol) == '') {

            return NULL;
        } else {

            $utc = (int) $utc + 3;

            $sunrise = date('H', strtotime($meridiem['sunrise']));
            $sunset = date('H', strtotime($meridiem['sunset']));

            if ($utc > $sunrise && $utc < $sunset) {
                $dayOrNight = 'day';
            } else {
                $dayOrNight = 'night';
            }

            return $dayOrNight . '_' . number_format($symbol, 0);
        }
    }

    protected function csvToArray($csv, $headersSpecimen) {

        //Convert 
        $expected = strstr($csv, $headersSpecimen);
        if ($expected == '') {
            $error = 'There was an error generating the CSV';
            $this->log($error);
            return array();
        }
        
        $rows = explode("\n", $csv);
        $headers = explode(';', $rows[0]);

        unset($rows[0]);

        $arrayResults = array();
        foreach ($rows as $key => $row) {
            if (trim($row) != '') {
                $params = explode(';', $row);
                foreach ($params as $key2 => $param) {
                    if ($headers[$key2] != '') {
                        $fieldName = $headers[$key2];
                        $uniqueKey = $key;
                        $arrayResults[$uniqueKey][$fieldName] = trim($param);
                    }
                }
            }
        }

        return $arrayResults;
    }

    public function getStationInfo($stationID = NULL, $keys = NULL) {
        if ($stationID == NULL) {
            $error = 'Station ID is required';
            $this->log($error);
            return NULL;
        } else {
            include dirname(__FILE__) . '/models/auth.php';
            $url = "http://192.168.20.89/abfrage.php?stationidstring=$stationID&ortsinfo=ja&paramtyp=mos_mix_mm&output=html&aufruf=auto";

            $gum = $stationID . '_info_' . sha1(end(explode('?', $url)));
            $curlResults = NULL;
            if (!Cache::read($gum, '3hour')) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_USERPWD, "{$karten['username']}:{$karten['password']}");
                curl_setopt($ch, CURLOPT_USERAGENT, "Weather.com.ph Curl Client 1.0");
                curl_setopt($ch, CURLOPT_TIMEOUT, 10); //times out after 10s 
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

                $curlResults = curl_exec($ch);
                curl_close($ch);
                Cache::write($gum, $curlResults, '3hour');
            } else {
                $curlResults = Cache::read($gum, '3hour');
            }

            $headersSpecimen = "id;name;typ;locator;wmo;reg;wmo1;wmo2;wmo3;icao;iat;aktiv;mos_gfs_kn;mos_ez_kn;mos_ez_mm;mos_preferred;lat;lon;alt;altp;xxx;spezial;org;land;bundesland;von;bis;wmoalt;wmoaltbis;metrilognummer;stationstyp;webname;webaktiv;dlat;dlon;ersatzstation;zeitzone;olsontimezone;webnosponsor;";

            $results = $this->csvToArray($curlResults, $headersSpecimen);

            if (count($results) > 1) {
                unset($results[2]);
            }
        }
        
        $results = $results[1];
        $return = array();
        
        if ($keys == NULL) {
            return $results;
        } else {
            if (is_array($keys)) {

                
                foreach ($keys as $key) {
                    $return[$key] = $results[$key];
                }
            } else {
                $return = $results[$keys];
            }
        }
        return $return;
    }

    protected function popValArray($array, $val, $xdata = NULL, $ydata = NULL) {
        foreach ($array as $key => $arr) {
            foreach ($arr as $ar) {
                $arrSet[] = array(
                'x' => (trim($xdata) != NULL) ? $xdata : strtotime($ar['Datum'] . ' ' . $ar['utc'] . ':' . $ar['min']),
                'y' => (trim($ydata) != NULL) ? $ydata : $ar[$val],
                'data' => $ar[$val],
                );
            }
        }
        return $arrSet;
    }

    protected function showWindDirection($wd = NULL) {

        if ($wd == NULL || trim($wd) == '') {

            return NULL;
        } else {

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

    public function arrayToAnyChartXML($conditions = null, $fields = array(), $order = null, $recursive = null) {

        $arrData = $fields['conditions']['arrData'];
        $type = strtolower($fields['conditions']['type']);

        $this->log(print_r($arrData['settings'], true), 'anychart');
        $xml_string =
        '<?xml version="1.0" encoding="ISO-8859-1"?>
                <anychart>
                <margin all="3" bottom="0" left="10" right="8"/>
                <settings>
                    <locale>
                        <date_time_format>
                            <format>%u</format>';

        if ($type == 'precipitation' || $type == 'precip') {
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
                                <x_axis enable="true">
                                    <scale type="DateTime" minimum_offset="0" maximum_offset="0" minor_interval="' . $arrData['settings']['minor_interval'] . '" minor_interval_unit="Hour" major_interval="1" major_interval_unit="Day"/>
                                    <title enabled="false"/>
                                    <labels enabled="True" show_cross_label="' . $arrData['settings']['show_cross_label'] . '" allow_overlap="true">
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


        if ($type == 'winddir') {
            $xml_string .= '        <y_axis enabled="false">
                                    <scale type="Linear" maximum="1" minimum="0"/>
                                    <title enabled="false"/>
                                    <labels enabled="false">
                                        <format>{%Value}{numDecimals:0}</format>
                                        <font family="Arial" color="#444444" size="11"/>
                                    </labels>
                                </y_axis>';
        } else if ($type == 'humidity') {
            $xml_string .= '       <y_axis>
                                    <scale type="Linear" maximum="100" minimum="0" maximum_offset="0.01" minimum_offset="0.01" />
                                    <title enabled="false"/>
                                    <labels>
                                        <format>{%Value}{numDecimals:0}</format>
                                        <font family="Arial" color="#444444" size="11"/>
                                    </labels>
                                </y_axis>';
        } else {
            $xml_string .= '       <y_axis>
                                    <!--scale type="Linear" maximum="auto" minimum="auto" maximum_offset="0.01" minimum_offset="0.01"/-->
                                    <title enabled="false"/>
                                    <labels>
                                        <format>{%Value}{numDecimals:0}</format>
                                        <font family="Arial" color="#444444" size="11"/>
                                    </labels>
                                </y_axis>';
        }

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
                <data_plot_settings default_series_type="' . $arrData['settings']['default_series_type'] . '">
                    ';

        // Settings
        // Temperature
        if ($type == 'temp' || $type == 'temperature') {
            $xml_string .= '<line_series>
                            <marker_settings enabled="false"/>
                            <line_style>
                                <line enabled="true" thickness="2" caps="round" joints="round"/>
                            </line_style>
                            <tooltip_settings enabled="true">
                                <format>
                                    <![CDATA[ {%YValue}{numDecimals:1} ]]>
                                </format>
                            </tooltip_settings>
                        </line_series>';
        }

        // Precipitation (rain6)
        if ($type == 'precipitation' || $type == 'precip') {
            $xml_string .= '<bar_series point_padding="0" scatter_point_width="4.7%">
                            <bar_style>
                                <fill enabled="true" type="Gradient">
                                    <gradient type="Radial">
                                        <key position="0" color="#0036d9"/>
                                        <!-- innen -->
                                        <key position="1" color="#002080"/>
                                    </gradient>
                                </fill>
                                <border enabled="False"/>
                                <effects enabled="False"/>
                            </bar_style>
                        </bar_series>';
        }

        // Air Pressure (qff)
        if ($type == 'airpressure') {
            $xml_string .= '<bar_series point_padding="0" scatter_point_width="4.7%">
                            <bar_style>
                                <fill enabled="true" type="Gradient">
                                    <gradient type="Radial">
                                        <key position="0" color="#F5E616"/>
                                        <!-- innen -->
                                        <key position="1" color="#E3D50B"/>
                                    </gradient>
                                </fill>
                                <border enabled="False"/>
                                <effects enabled="False"/>
                            </bar_style>
                        </bar_series>';
        }

        // Humidity, Wind and Wind Direction
        if ($type == 'humidity' || $type == 'wind') {
            $xml_string .= '<line_series>
                            <marker_settings enabled="false"/>
                            <line_style>
                                <line enabled="true" thickness="2" caps="round" joints="round"/>
                            </line_style>
                        </line_series>';
        } elseif ($type == 'winddir') {
            $xml_string .= '<line_series>
                            <marker_settings enabled="true"/>
                            <line_style>
                                <line enabled="true" thickness="2" caps="round" joints="round"/>
                            </line_style>
                        </line_series>';
        }

        // Sunshine
        if ($type == 'sunshine') {
            $xml_string .= '<bar_series scatter_point_width="0.4%" group_padding="0" point_padding="0">
                            <bar_style>
                                <fill enabled="true" type="Solid" color="#fff000" thickness="1"></fill>
                                <border enabled="True" type="Gradient">
                                    <gradient angle="90">
                                        <key position="0" color="#ffd500"/>
                                        <key position="0.3" color="#fff000"/>
                                        <key position="1" color="#fff000"/>
                                    </gradient>
                                </border>
                                <effects enabled="False"/>
                            </bar_style>
                        </bar_series>';
        }

        if ($type == 'globalradiation') {
            $xml_string .= '<bar_series scatter_point_width="0.4%" group_padding="0" point_padding="0">
                            <bar_style>
                                <fill enabled="true" type="Solid" color="#182DCC" thickness="1"></fill>
                                <effects enabled="False"/>
                            </bar_style>
                        </bar_series>';
        }

        $xml_string .= '
                </data_plot_settings>
                <styles>';

        // Styles
        if ($type == 'temperature' || $type == 'temp') {
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
        } elseif ($type == 'wind') {

            $xml_string .= '
                    <line_style name="ffline" color="#966400"/>
                    <line_style name="g1hline" color="#c800aa"/>';
        } elseif ($type == 'winddir') {

            $xml_string .= '
                    <line_style name="dirline" color="green">
                        <line enabled="false"/>
                    </line_style>
                    <marker_style name="wind_1"><!-- EAST -->
                        <marker type="Image" image_url="../theme/weatherph/img/w1.png" size="23"/>
                    </marker_style>
                    <marker_style name="wind_2"><!-- SOUTH EAST -->
                        <marker type="Image" image_url="../theme/weatherph/img/w2.png" size="23"/>
                    </marker_style>
                    <marker_style name="wind_3"><!-- SOUTH -->
                        <marker type="Image" image_url="../theme/weatherph/img/w3.png" size="23"/>
                    </marker_style>
                    <marker_style name="wind_4"><!-- SOUTH WEST -->
                        <marker type="Image" image_url="../theme/weatherph/img/w4.png" size="23"/>
                    </marker_style>
                    <marker_style name="wind_5"><!-- WEST -->
                        <marker type="Image" image_url="../theme/weatherph/img/w5.png" size="23"/>
                    </marker_style>
                    <marker_style name="wind_6"><!-- NORTH WEST -->
                        <marker type="Image" image_url="../theme/weatherph/img/w6.png" size="23"/>
                    </marker_style>
                    <marker_style name="wind_7"><!-- NORTH -->
                        <marker type="Image" image_url="../theme/weatherph/img/w7.png" size="23"/>
                    </marker_style>
                    <marker_style name="wind_8"><!-- NORTH EAST -->
                        <marker type="Image" image_url="../theme/weatherph/img/w8.png" size="23"/>
                    </marker_style>
                    <marker_style name="wind_9"><!-- NO WIND DIRECTION -->
                        <marker type="Image" image_url="../theme/weatherph/img/w9.png" size="23"/>
                    </marker_style>
                    ';
        } elseif ($type == 'humidity') {

            $xml_string .= '<line_style name="rhline" color="#00c800"/>';
        }

        $xml_string .= '
                </styles>
                <data>';

        //$this->log($arrData);

        foreach ($arrData['sets'] as $key => $sets) {

            $xml_string .= '<series';

            foreach ($arrData['series'][$key] as $index => $attr) {
                $xml_string .= (trim($attr) != '') ? ' ' . $index . '="' . $attr . '"' : '';
            }

            $xml_string .= '>';

            if (isset($arrData['additional'][$key])) {

                foreach ($arrData['additional'][$key] as $index => $addtnl) {

                    $xml_string .= '<' . $index . ' ';

                    foreach ($addtnl as $key2 => $add) {
                        $xml_string .= ' ' . $key2 . '="' . $add . '"';
                    }
                    $xml_string .= '/>';
                }
            }

            foreach ($sets as $set) {
                $xml_string .= '<point x="' . $set['x'] . '" y="' . $set['y'] . '">';
                $xml_string .= (isset($set['marker'])) ? '<marker style="' . $set['marker'] . '" />' : '';
                $xml_string .= '<!-- ' . date('Y-m-d H:i:s', $set['x']) . '-->';
                $xml_string .= '</point>';
            }

            $xml_string .='</series>';
        }

        $xml_string .= ' 
               </data>
                </chart>
                </charts> 
           </anychart>';

        //$this->log($xml_string);

        $xml = simplexml_load_string($xml_string);

        return $xml;
    }

    // Used to get the maximum and minimum in a set of array.
    public function amaxmin($array, $val, $arg) {

        foreach ($array as $key => $arr) {

            foreach ($arr as $ar) {
                if (!isset($arrmaxmin[$key])) {
                    if (trim($ar[$val]) != '') {
                        $arrmaxmin[$key] = array(
                        'x' => strtotime($ar['Datum'] . ' ' . $ar['utc'] . ':' . $ar['min']),
                        'y' => $ar[$val],
                        'data' => $ar[$val],
                        );
                        break;
                    }
                }
            }


            foreach ($arr as $ar) {
                if (trim($ar[$val]) != '') {
                    if ($arg == 'max' && $ar[$val] >= $arrmaxmin[$key]['y']) {
                        $arrmaxmin[$key] = array(
                        'x' => strtotime($ar['Datum'] . ' ' . $ar['utc'] . ':' . $ar['min']),
                        'y' => trim($ar[$val]),
                        'data' => $ar[$val],
                        );
                    } elseif ($arg == 'min' && $ar[$val] <= $arrmaxmin[$key]['y']) {
                        $arrmaxmin[$key] = array(
                        'x' => strtotime($ar['Datum'] . ' ' . $ar['utc'] . ':' . $ar['min']),
                        'y' => trim($ar[$val]),
                        'data' => $ar[$val],
                        );
                    }
                }
            }
        }

        $arrData = array();
        foreach ($arrmaxmin as $maxmin) {
            $arrData[] = $maxmin;
        }

        return $arrData;
    }
    
}
