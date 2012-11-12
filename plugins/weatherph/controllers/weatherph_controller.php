<?php

/**
 * Weatherph Controller
 *
 * @category Controller
 * @package  Weatherph
 * @version  1.0
 * @author   Sonny Gauran <sgauran@meteomedia.com.ph>
 * @link     http://www.weather.com.ph
 */

App::uses('Browser', 'Meteomedia.Lib');
class WeatherphController extends WeatherphAppController {

    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Weatherph';

    public $helpers = array('Javascript');
    /**
     * Models used by the Controller
     *
     * @var array
     * @access public
     */
    public $uses = array('Setting','Media');

    public function admin_index() {
       //$this->set('title_for_layout', __('Weatherph', true));
    }

    public function index() {

        //echo WWW_ROOT;
        $layerFiles = scandir(ROOT.DS.APP_DIR.DS.'views'.DS.'themed'.DS.'weatherph'.DS.'webroot'.DS.'img'.DS.'layers');

        $filenames = array();
        $areas = array();
        $types = array();

        foreach ($layerFiles as $layerFile) {
            // 20120814030000all_pressure.png
            $_year = '([0-9]{4})';
            $_month = '([0-9]{2})';
            $_day = '([0-9]{2})';
            $_hour = '([0-9]{2})';
            $_min = '([0-9]{2})';
            $_sec = '([0-9]{2})';
            $_area = '([a-z]+)';
            $_type = '(satellite|pressure|temperature)';


            $filePattern = "/{$_year}{$_month}{$_day}{$_hour}{$_min}{$_sec}{$_area}_{$_type}/";
            $matches = array();
            if (preg_match($filePattern, $layerFile, $matches)) {
                array_shift($matches); // removes the actual complete filename
                //print_r($matches)
                list($year, $month, $day, $hour, $min, $sec, $area, $type) = $matches;

                $time = "$year$month$day $hour$min$sec";
                error_log($time);
                $time = strtotime('+8 hours', strtotime($time));

                $pst_year = date('Y', $time);
                $pst_month = date('m', $time);
                $pst_day = date('d', $time);
                $pst_hour = date('H', $time);
                $pst_min = date('i', $time);


//                list($year, $month, $day, $hour, $min, $sec, $area, $type)  = date('Ymd ', $time);`

                if (!in_array($area, $areas)) {
                    $areas[] = $area;
                }
                if (!in_array($type, $types)) {
                    $types[] = $type;
                }

                $file_date = "$pst_year$pst_month$pst_day $pst_hour$pst_min"; //Ymd Hi
                $current_time = date('Ymd Hi', strtotime('+8 hours'));
                $current_time2 = date('Ymd Hi', strtotime('+5 hours'));
                $current = compact('year', 'month', 'day', 'hour', 'min', 'pst_year', 'pst_month', 'pst_day', 'pst_hour', 'pst_min');
                if($type=='temperature' || $type=='pressure'){
                    if($current_time < $file_date){
                        $filenames[$type][] = $current;

                    }else{
                        continue;
                    }

                }else{
                  if(key_exists('satellite', $filenames) && in_array($current, $filenames[$type])) continue;
                  $filenames[$type][] = $current;
                }

            }
        }
        
        $this->set(compact('filenames', 'areas', 'types'));
        //print_r($areas);
        //print_r($types);
        //print_r($filenames);
        //exit;
        $blogEntries = $this->Node->find('all', array(
            'order' => 'Node.created DESC',
            'conditions' => array('Node.type' => array('news', 'announcements', 'weathertv')),
            'limit' => 5,
        ));

        // $time = date('YmdHis');
        $everyHour = date('YmdH0000');

        $wetter4 = 'http://alpha.meteomedia-portal.com/services/wetter4.php?dt='.$everyHour.'&';
        $temperature = 'api_key=portal-efd339395c80ad957acb695bb9758399&q=meh_ifm&leg=nil&a=image&x=554&y=554&srs=EPSG:900913&';
        $pressure    = $temperature.'p=QFF&';

        // x1=111.32714843750325&x2=135.67285156249676&y2=24.41201768480203&y1=0.8402895756535625

        App::import('Model', 'Weatherph.Resource');
        $Resource = new Resource();

        $keyTemperature = $Resource->generateKey('data-layer', 'temperature', $wetter4.$temperature);
        $keyPressure    = $Resource->generateKey('data-layer', 'pressure', $wetter4.$pressure);

        $resources = array(
            'data-layers' => array(
                'temperature' => $keyTemperature,
                'pressure'    => $keyPressure,
            ),
        );
        /**
         * Note:
         *
         * index.js requires the following variable:
         *      - resource - contains an array of (data-layer => (temperature, pressure)) for retreiving the image key.
         *      - featureBlog - to display the featured blogs
         */
        
         $og_image = array('property'=>'og:image','content'=>'http://alpha.weather.com.ph/theme/weatherph/img/logo.png');
         $og_title = array('property'=>'og:title','content'=>'Weather Philippines Foundation');
         $og_description = array('property'=>'og:description','content'=>'The Weather Philippines Foundation (WPF) aims to deliver critical and accurate weather forecasts to the Filipino community, with the hope of improving nationwide disaster preparedness, and timely response to variable weather conditions.');
         
        

        $meta_for_description = $this->description('description', 'The Weather Philippines Foundation (WPF) aims to deliver critical and accurate weather forecasts to the Filipino community, with the hope of improving nationwide disaster preparedness, and timely response to variable weather conditions.');
        $this->set(compact('blogEntries', 'resources', 'meta_for_description','og_image','og_title','og_description'));
        $this->set('title_for_layout','Home');
    }


    //changed $provider ='pagasa' to $provider = 'meteomedia' for filtering of pagasa stations
    public function getStations($provider = 'meteomedia') {
        $this->layout = 'json/ajax';

        $this->set('title_for_layout', __('Weatherph', true));
        App::import('Model', 'Weatherph.WeatherphStation');
        App::import('Model', 'Weatherph.Station');

        $WeatherphStation = new Station();

        $fields = array('wmo1', 'lon', 'lat', 'name','webaktiv');
        if ($provider == 'others') {
            $stations = $WeatherphStation->find('all', array(
                'conditions' => array('NOT' => array('org' => 'JRG')),
                'fields' => $fields,
            ));
        } else {
            $stations = $WeatherphStation->find('all', array(
                'conditions' => array('org' => 'JRG'),
                'fields' => $fields,
            ));
        }
        $locations = array();
        $stations_result = array();
        foreach ($stations as $station) {
            $station = $station['Station'];
            if ($station['webaktiv'] != 2) {
                $current = array(
                    'id' => $station['wmo1'],
                    'name' => $station['name'],
                    'coordinates' => array(
                        'longitude' => $station['lon'],
                        'latitude' => $station['lat'],
                    )
                );

                $currentLocationInString = $station['lat'].','.$station['lon'];
                if (!in_array($currentLocationInString, $locations)) {
                    $locations[] = $currentLocationInString;
                    $stations_result[] = $current;
                }
            }
        }


        Configure::write('debug', 0);
        $this->set('stations', json_encode($stations_result));
    }




    public function getReadings($stationID = '920001') {
        $this->layout = 'json/ajax';

        App::import('Model', 'Weatherph.Reading');

        $WeatherphStationReading = new Reading();
        $currentReading = $WeatherphStationReading->find('all', array('conditions' => array(
            'ort1 LIKE' => $stationID.'%',
        )));
        //       Configure::write('debug', 0);
        $this->set('readings', json_encode($currentReading));
    }

    /**
     * ajax
     */
    public function getForecast($stationID = '984290', $numDays = 1, $utch = '3h') {

        //For


        $this->layout = 'json/ajax';
        App::import('Model', 'Weatherph.WeatherphStationForecast');

        $WeatherphStationForecast = new WeatherphStationForecast();
        $forecasts = $WeatherphStationForecast->get('all', array('conditions' => array(
        'id' => $stationID,
        'target_days' => $numDays,
        'utch' => $utch,
        )));
        $this->set('forecasts', json_encode($forecasts));
    }

    public function admin_getDetailedForecast($stationID = '984290', $timeRes = '1h', $startDatum = NULL) {

        $this->layout = 'plain';
        App::import('Model', 'Weatherph.WeatherphStationForecast');

        $WeatherphStationForecast = new WeatherphStationForecast();
        $detailedForecast = $WeatherphStationForecast->getDetailedForecast('all', array('conditions' => array(
        'id' => $stationID,
        'timeRes' => $timeRes,
        'startDatum' => $startDatum,
        )));


        $this->set('detailedForecast', $detailedForecast);
    }

    public function getDetailedForecast($stationID = '984290', $type = NULL, $timeRes = '1h', $startDatum = NULL) {

//        if($this->referer() != '/swf/AnyChart.swf') return; // limits the access of the XML for anychart usage
        App::import('Model', 'Weatherph.WeatherphStationForecast');

        $WeatherphStationForecast = new WeatherphStationForecast();
        $detailedForecast = $WeatherphStationForecast->getDetailedForecast('all', array('conditions' => array(
        'id' => $stationID,
        'type' => $type,
        'timeRes' => $timeRes,
        'startDatum' => $startDatum,
        )));

        if ($type != NULL) {

            $WeatherphStationForecast = new WeatherphStationForecast();
            $anyChartXML = $WeatherphStationForecast->arrayToAnyChartXML('all', array('conditions' => array(
            'arrData' => $detailedForecast,
            'type' => $type,
            )));

            $this->layout = 'xml';
            $this->set('outputData', $anyChartXML);
        } else {

            $this->layout = 'plain';
            $this->set('outputData', $detailedForecast);
        }
    }

    public function detailedForecast($stationID = '984290') {

        $this->layout = 'plain';
        $this->set('stationID', $stationID);
    }

    public function getDetailedReading($stationID = '984290', $type = NULL, $timeRes = '3h', $startDatum = NULL, $endDatum = NULL) {
        App::import('Model', 'Weatherph.WeatherphStationReading');

        $WeatherphStationReading = new WeatherphStationReading();
        $detailedReading = $WeatherphStationReading->getDetailedReading('all', array('conditions' => array(
        'id' => $stationID,
        'type' => $type,
        'timeRes' => $timeRes,
        'startDatum' => $startDatum,
        'endDatum' => $endDatum,
        )));
        
        if ($type != NULL) {
            $WeatherphStationReading = new WeatherphStationReading();
            $anyChartXML = $WeatherphStationReading->arrayToAnyChartXML('all', array('conditions' => array(
            'arrData' => $detailedReading,
            'type' => $type,
            )));

            $this->layout = 'xml';
            $this->set('outputData', $anyChartXML);
        } else {
            $this->layout = 'plain';
            $this->set('outputData', $detailedReading);
        }
    }

    /**
     * ajax
     *
     */
    public function detailedReading($stationID = '984290', $startDate = NULL, $endDate = NULL) {

        $this->layout = 'plain';

        $startDate = ($startDate == NULL) ? date('Ymd', strtotime('-3 Days', strtotime(date('Ymd')))) : $startDate;
        $endDate = ($endDate == NULL) ? date('Ymd') : $endDate;

        $set = array(
        'stationID' => $stationID,
        'startDate' => $startDate,
        'endDate' => $endDate,
        );

        $this->set('set', $set);
    }

    public function view($stationID = '984290') {
        App::import('Model', 'Weatherph.WeatherphStationForecast');
        App::import('Model', 'Weatherph.Station');

        $WeatherphStationForecast = new WeatherphStationForecast();
        $WeatherphStation = new Station();
        $station = $WeatherphStation->find('first', array(
            'conditions' => array('wmo1' => $stationID),
            'fields' => 'wmo1'
        ));

        if(empty($station)) $this->redirect(array('controller' => 'weatherph', 'action' => '
'));
        $dataSets = $WeatherphStationForecast->getWeeklyForecast('all', array('conditions' => array(
            'id' => $stationID,
        )));

        $today = date('Ymd');
        $enddate = date('Ymd', strtotime('+2 days', strtotime($today)));
        $forecastRange = range($today, $enddate);
        $title_for_layout = $dataSets['station_name'];

        //$this->log(print_r($forecastRange, true));

        $x = strftime("(%a)%b %d");  
        $y = strftime("(%a)%b %d, %Y", strtotime('+4 days', strtotime($today)));
       
        $og_image = array('property'=>'og:image','content'=>'http://alpha.weather.com.ph/theme/weatherph/img/logo.png');
        $og_title = array('property'=>'og:title','content'=>'Weather Philippines Foundation');
        $description = "Latest weather update for $title_for_layout Philippines. Five (5) days forecast from $x to $y";
        $meta_for_description = $this->description('description', $description);
        $og_description = array('property'=>'og:description','content'=> $description);
        $this->set(compact('forecastRange', 'dataSets', 'title_for_layout','meta_for_description','og_image','og_title','og_description'));

    }

    public function about() {
        $this->layout = 'default';
           $og_image = array('property'=>'og:image','content'=>'http://alpha.weather.com.ph/theme/weatherph/img/logo.png');
        $og_title = array('property'=>'og:title','content'=>"Learn more about Weather Philippines Foundation and its founders.");
        $meta_for_description = $this->description('description', 'WeatherPhilippines');
        $og_description = array('property'=>'og:description','content'=>"Learn more about Weather Philippines Foundation and its founders. ");
        $this->set(compact('meta_for_description','og_image','og_title','og_description'));
    }

    public function news() {
        $this->set('title_for_layout',__('Weatherph',true));

        //$this->layout = 'default';

        $blogLists = $this->Node->find('all', array(
            'order' => 'Node.created DESC',
            'conditions' => array('Node.type' => array('news', 'announcements')),
            'limit' => 5,
        ));

    //debug($blogLists);
        $meta_for_description = $this->description('description', "WeatherPH provides comprehensive up-to-date news about the Philippines' current weather condition.");
         $og_image = array('property'=>'og:image','content'=>'http://alpha.weather.com.ph/theme/weatherph/img/logo.png');
         $og_description = array('property'=>'og:description','content'=>"WeatherPH provides comprehensive up-to-date news about the Philippines' current weather condition.");
         $og_title = array('property'=>'og:title','content'=>' News | Weather Philippines Foundation');
         $this->set(compact('blogLists','meta_for_description','og_image','og_title','og_description'));
    }

    public function payongpanahon() {
        $this->set('title_for_layout',__('Weatherph',true));

        //$this->layout = 'default';

        $blogLists = $this->Node->find('all', array(
            'order' => 'Node.created DESC',
            'conditions' => array('Node.type' => 'news'),
            'limit' => 5,
        ));

    //debug($blogLists);
        $og_image = array('property'=>'og:image','content'=>'http://alpha.weather.com.ph/theme/weatherph/img/logo.png');
        $og_title = array('property'=>'og:title','content'=>'Weather Philippines Foundation');
        $og_description =array ('property'=> 'og:title','content'=>'Regular-weather updates about weather conditions in the Philippines');
        $meta_for_description = $this->description('description', 'Regular-weather updates about weather conditions in the Philippines');
        $this->set(compact('blogLists','meta_for_description','og_image','og_title','og_description'));
    }

    public function mataNgBagyo(){
        $this->layout = 'default';


        $blogLists = $this->Node->find('all', array(
            'order' => 'Node.created DESC',
            'conditions' => array('Node.type' => 'announcements'),
            'limit' => 5,
        ));

    //debug($blogLists);
        $meta_for_description = $this->description('description', 'Severe-weather updates about weather conditions in the Philippines');
        $og_image = array('property'=>'og:image','content'=>'http://alpha.weather.com.ph/theme/weatherph/img/logo.png');
        $og_title = array('property'=>'og:title','content'=>'Weather Philippines Foundation');
        $og_description = array('property'=>'og:description','content'=>'dasda');
        $this->set(compact('blogLists','meta_for_description','og_image','og_title','og_description'));


    }

    public function impressum() {
        $this->layout = 'default';
    }

   public function getDmoForecast($id){
       //debug($id);
       App::import('Model', 'Weatherph.WeatherphStationForecast');
       App::import('Model', 'Weatherph.NearestStation');
       App::import('Model','Nima.NimaName');

       $NearestStation = new NearestStation();
       $DmoForecast = new WeatherphStationForecast();
       $search_location = new NimaName();

       $result = $NearestStation->find('all', array(
           'conditions' => array(
               'reference' => $id
        )));

       $location = $search_location->find('all', array(
        'conditions' => array(
            'Name.id =' => $id,
        )));

       $location = $location[0];

       $this->log('location >> ' . print_r($location, TRUE));

       //debug($result);= number_format($distance,1,'.','')
       $station_id = $result[0]['NearestStation']['station_id'];
       $distance = $result[0]['NearestStation']['distance'];
       $distance = number_format($distance,1,'.','').'km';

       $dataSets = $DmoForecast->dmoForecast('all', array('conditions' => array(
           'id' => $station_id,
           'coordinates'    => array(
               'lat' => $location['Name']['lat'],
               'lon' => $location['Name']['long'],
               )
       )));

//       $this->log(print_r($dataSets, true));

               $meta_for_description = $this->description('description', "{$dataSets['station_name']} Detailed forecast");
                  $og_image = array('property'=>'og:image','content'=>'http://alpha.weather.com.ph/theme/weatherph/img/logo.png');
        $og_title = array('property'=>'og:title','content'=>'Weather Philippines Foundation');
        $og_description = array('property'=>'og:description', 'content'=> "{$dataSets['station_name']} Detailed forecast");
        $this->set(compact('dataSets','location', 'distance','meta_for_description','og_image','og_title','og_description'));
   }

    public function getStationReadings($station_id = NULL, $time_frame = "10m", $target_date = NULL, $days_range = NULL){

        $this->layout = "plain";

        App::import('Model', 'Weatherph.WeatherphStationReading');

        $WeatherphStationReading = new WeatherphStationReading();
        $readings = $WeatherphStationReading->fetch('all', array('conditions' => array(
        'id' => $station_id,
        'target_date' => $target_date,
        'days_range' => $days_range,
        'time_frame' => $time_frame,
        )));

       // $this->log($readings);
        
        $this->set(compact('readings'));

    }

   public function getAllStation($provider = 'pagasa'){

        $this->layout = 'plain';

        App::import('Model', 'Weatherph.WeatherphStation');
        App::import('Lib', 'Meteomedia.Abfrage');
        App::import('Lib', 'Meteomedia.Curl');

        $WeatherphStation = new WeatherphStation();
        $stations = $WeatherphStation->find('all', array('conditions' => array(
        'provider' => $provider,
        )));

        $stationsId = Set::extract($stations, '{n}.id');

        $Abfrage = new Abfrage($stationsId);

        //Grab stations readings
        $url = $Abfrage->generateURL($WeatherphStation->generateDate('reading', '10m'), array(
        'Temperature' => array(
            'low'
        ),
        'Wind' => array(
            'speed', 'direction'
        ),
        'Gust' => array(
            '3 hours', '6 hours'
        ),
        'Rainfall' => array(
            'Period', '3 hours', '6 hours'
        ),
        'Weather Symbols' => array(
            'Set 1', 'Set 2'
        ),
        'Humidity'
        ));

        //debug($url);exit;

        $curlResults = NULL;
        $curlResults = Curl::getData($url, 60);

        //$curlResults = file_get_contents(Configure::read('Data.readings').'/readings.csv');

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
            $Reading->create();
            $data = array(
                'datum' => $result['Datum'],
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

//        foreach ($curlReadingsAsArray as $curlArray) {
//
//            $Reading->create();
//
//            $data = array(
//            'datum' => $curlArray['Datum'],
//            'utc' => $curlArray['utc']
//            );
//
//            $Reading->save($data);
//          }

        //debug($curlResults); exit;
        exit;

   }


   function weathertv($slug = ''){
       
       $meta_for_description = $this->description('description', 'Vlogs (Video Blogs) regarding the weather condition of the Philippines');
       $og_image = array('property'=>'og:image','content'=>'http://alpha.weather.com.ph/theme/weatherph/img/logo.png');
       $og_title = array('property'=>'og:title','content'=>'Weather Philippines Foundation');

       
       if (!$slug){
       $latest_video = $this->Node->find('first', array(
            'order' => 'Node.created DESC',
            'conditions' => array('Node.type' => 'weathertv')
        ));
       }else {
       $latest_video = $this->Node->find('first', array(
            'order' => 'Node.created DESC',
            'conditions' => array('Node.slug' => $slug)
        ));
       }
       if (empty($latest_video)){
           $this->Session->setFlash('video not available');
           $this-->redirect(array(
               'controller' => 'weatherph',
               'action' => 'weathertv',
               ''
           ));
       }
       
       $videos = $this->Node->find('all', array(
            'order' => 'Node.created DESC',
            'conditions' => array(
                'AND' => array(
                    array('Node.type' => 'weathertv'),
                    array('Node.id != ' => $latest_video['Node']['id'])
                )
            ),
        ));
              $og_description = array('property'=>'og:description','content'=> "WeatherTv: {$latest_video['Node']['title']}");
       
        $this->set(compact('latest_video', 'videos', 'meta_for_description','og_image','og_title','og_description'));
   }
   function webcam(){
        $og_image = array('property'=>'og:image','content'=>'http://alpha.weather.com.ph/theme/weatherph/img/logo.png');
        $og_title = array('property'=>'og:title','content'=>'Weather Philippines Foundation');
        $meta_for_description = $this->description('description', 'View current weather conditions across the Philippines');
        $og_description = array('property'=>'og:desccription','content'=>'View current weather conditions across the Philippines');
        $this->set(compact('meta_for_description','og_image','og_title','og_description'));
       
       
   }

   //JETT

    public function measurements($startdate = NULL, $enddate = NULL, $stationID = '984290', $timeinterval = '10m') {

        $this->layout = 'plain';

        App::import('Model', 'Weatherph.WeatherphStationReading');

        $startdate = ($startdate == NULL)? date('Y-m-d') : date('Y-m-d', strtotime($startdate));
        $enddate = ($enddate == NULL)? date('Y-m-d', strtotime('-1 day')) : date('Y-m-d', strtotime($startdate));

        $WeatherphStationMeasurement = new WeatherphStationReading();
        $dataSets = $WeatherphStationMeasurement->getStationReadings(array('conditions' => array(
        'id' => $stationID,
        'startdate' => $startdate,
        'enddate' => $enddate,
        'timeinterval' =>$timeinterval,
        )));
        
        $this->set(compact('dataSets'));
    }


    public function getMeasurements($stationID = '920001') {
        $this->layout = 'json/ajax';

        App::import('Model', 'Weatherph.Measurement');

        $WeatherphStationMeasurement = new Measurement();
        $currentMeasurement = $WeatherphStationMeasurement->find('all', array('conditions' => array(
            'ort1 LIKE' => $stationID.'%',
        )));
        //       Configure::write('debug', 0);
        $this->set('measurements', json_encode($currentMeasurement));
    }

    public function getDetailedMeasurement($stationID = '984290', $type = NULL, $timeRes = '3h', $startDatum = NULL, $endDatum = NULL) {
        App::import('Model', 'Weatherph.WeatherphStationMeasurement');

        $WeatherphStationMeasurement = new WeatherphStationMeasurement();
        $detailedMeasurement = $WeatherphStationMeasurement->getDetailedMeasurement('all', array('conditions' => array(
        'id' => $stationID,
        'type' => $type,
        'timeRes' => $timeRes,
        'startDatum' => $startDatum,
        'endDatum' => $endDatum,
        )));

        if ($type != NULL) {
            $WeatherphStationMeasurement = new WeatherphStationMeasurement();
            $anyChartXML = $WeatherphStationMeasurement->arrayToAnyChartXML('all', array('conditions' => array(
            'arrData' => $detailedMeasurement,
            'type' => $type,
            )));

            $this->layout = 'xml';
            $this->set('outputData', $anyChartXML);
        } else {
            $this->layout = 'plain';
            $this->set('outputData', $detailedMeasurement);
        }
    }

    public function detailedMeasurement($stationID = '984290', $startDate = NULL, $endDate = NULL) {

        $this->layout = 'plain';

        $startDate = ($startDate == NULL) ? date('Ymd', strtotime('-3 Days', strtotime(date('Ymd')))) : $startDate;
        $endDate = ($endDate == NULL) ? date('Ymd') : $endDate;

        $set = array(
        'stationID' => $stationID,
        'startDate' => $startDate,
        'endDate' => $endDate,
        );

        $this->set('set', $set);
    }

    
    public function archives(){
        
        $this->paginate = array(
            'fields' => array('title', 'excerpt', 'type', 'slug'),
            'limit' => 10,
            'conditions' => array(
                'Node.type' => array('news', 'announcements', 'weathertv')
            )
        );
        $archives = $this->paginate('Node');
        
        $og_image = array('property'=>'og:image','content'=>'http://alpha.weather.com.ph/theme/weatherph/img/logo.png');
        $og_title = array('property'=>'og:title','content'=>'Weather Philippines Foundation');
        $meta_for_description = $this->description('description', 'List of latest news and announcements for the weather conditions across the Philippines');
        $og_description = array('property'=>'og:desccription','content'=>'List of latest news and announcements for the weather conditions across the Philippines');
        $this->set(compact('meta_for_description','og_image','og_title','og_description','archive','archives'));
    }
}
