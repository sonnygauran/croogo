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
class WeatherphController extends WeatherphAppController {
/**
 * Controller name
 *
 * @var string
 * @access public
 */
    public $name = 'Weatherph';
/**
 * Models used by the Controller
 *
 * @var array
 * @access public
 */
    public $uses = array('Setting');
    


    public function admin_index() {
        $this->set('title_for_layout', __('Weatherph', true));
    }

    public function index() {
        $this->set('title_for_layout', __('Weatherph', true));
        $this->set('weatherphVariable', 'value here');
    }
    
    public function getStations($provider = 'pagasa'){
        $this->layout = 'json/ajax';

        $this->set('title_for_layout', __('Weatherph', true));
        App::import('Model', 'Weatherph.WeatherphStation');
        
        $WeatherphStation = new WeatherphStation();
        $stations = $WeatherphStation->find('all', array('conditions' => array(
            'provider' => $provider,
        )));
        Configure::write('debug', 0);
        $this->set('stations', json_encode($stations));
    }
    
    public function getReadings($stationID = '920001'){
        $this->layout = 'json/ajax';

        App::import('Model', 'Weatherph.WeatherphStationReading');
        
        $WeatherphStationReading = new WeatherphStationReading();
        $currentReading = $WeatherphStationReading->find('all', array('conditions' => array(
            'id' => $stationID,
        )));
 //       Configure::write('debug', 0);
        $this->set('readings', json_encode($currentReading));
        
    }
    public function admin_getTwoWeekReadings($date = null){
        //$this->layout = 'json/ajax';
        
        App::import('Model', 'Weatherph.WeatherphStationReading');
        
        $WeatherphStationReading = new WeatherphStationReading();
        $TwoWeekReadings = $WeatherphStationReading->get('all', array('conditions' => array(
            'days_target' => 1,
        )));
 //       Configure::write('debug', 0);
        //debug($TwoWeekReadings);
        $this->set('two_week_readings', $TwoWeekReadings);
        
    }
    public function admin_getReadings($date = null){
        //$this->layout = 'json/ajax';
        
        $date = ($date == null)? date('Ymd') : $date; 
        
        App::import('Model', 'Weatherph.WeatherphStationReading');
        
        $WeatherphStationReading = new WeatherphStationReading();
        $DateReadings = $WeatherphStationReading->get('all', array('conditions' => array(
            'date' => $date,
        )));
 //       Configure::write('debug', 0);
        //debug($TwoWeekReadings);
        $this->set('date_readings', $DateReadings);
        
    }
    
    public function getForecast($stationID = '984290', $numDays = 1, $utch = '3h'){
        
        //$this->layout = 'plain';
        $this->layout = 'json/ajax';
        App::import('Model', 'Weatherph.WeatherphStationForecast');
        
        $WeatherphStationForecast = new WeatherphStationForecast();
        $forecasts = $WeatherphStationForecast->get('all', array('conditions' => array(
            'id' => $stationID,
            'target_days' => $numDays,
            'utch' => $utch,
        )));
        //$this->log(print_r($forecasts, true));
        $this->set('forecasts', json_encode($forecasts));
        
        
    }
    
    public function admin_getDetailedForecast($stationID = '984290', $timeRes = '1h', $startDatum = NULL){
        
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
    
    public function getDetailedForecast($stationID = '984290', $type = NULL, $timeRes = '3h', $startDatum = NULL){
        
        
        App::import('Model', 'Weatherph.WeatherphStationForecast');
        
        $WeatherphStationForecast = new WeatherphStationForecast();
        $detailedForecast = $WeatherphStationForecast->getDetailedForecast('all', array('conditions' => array(
            'id' => $stationID,
            'type' => $type,
            'timeRes' => $timeRes,
            'startDatum' => $startDatum,
        )));
        
        if($type != NULL){
        
            $WeatherphStationForecast = new WeatherphStationForecast();
            $anyChartXML = $WeatherphStationForecast->arrayToAnyChartXML('all', array('conditions' => array(
                'arrData' => $detailedForecast,
                'type' => $type,
            )));
            
            $this->layout = 'xml';
            $this->set('outputData', $anyChartXML);
        
        }else{
            
            $this->layout = 'plain';
            $this->set('outputData', $detailedForecast);
            
        }
    }
    
    public function detailedForecast($stationID = '984290'){
        
        $this->layout = 'plain';
        $this->set('stationID', $stationID);
        
    }
   
    public function getDetailedReading($stationID = '984290', $type = NULL, $timeRes = '3h', $startDatum = NULL, $endDatum = NULL){
        
        
        App::import('Model', 'Weatherph.WeatherphStationReading');
        
        $WeatherphStationReading = new WeatherphStationReading();
        $detailedReading = $WeatherphStationReading->getDetailedReading('all', array('conditions' => array(
            'id' => $stationID,
            'type' => $type,
            'timeRes' => $timeRes,
            'startDatum' => $startDatum,
            'endDatum' => $endDatum,
        )));
        
        if($type != NULL){
        
            $WeatherphStationReading = new WeatherphStationReading();
            $anyChartXML = $WeatherphStationReading->arrayToAnyChartXML('all', array('conditions' => array(
                'arrData' => $detailedReading,
                'type' => $type,
            )));
            
            $this->layout = 'xml';
            $this->set('outputData', $anyChartXML);
        
        }else{
            
            $this->layout = 'plain';
            $this->set('outputData', $detailedReading);
            
        }
    }
    
    public function detailedReading($stationID= '984290',$startDate=NULL, $endDate=NULL){
        
        $this->layout = 'plain';
        
        $startDate = ($startDate == NULL)? date('Ymd',  strtotime('-3 Days', strtotime(date('Ymd')))) : $startDate;
        $endDate = ($endDate == NULL)? date('Ymd') : $endDate;
        
        $set = array(
            'stationID' => $stationID,
            'startDate' => $startDate,
            'endDate' => $endDate,
        );
        
        $this->set('set', $set);
        
        
        
    }
    
    public function view($stationID = '984290') {
        
        App::import('Model', 'Weatherph.WeatherphStationForecast');
        
        $WeatherphStationForecast = new WeatherphStationForecast();
        $dataSets = $WeatherphStationForecast->getWeeklyForecast('all', array('conditions' => array(
            'id' => $stationID,
        )));
        
        //$this->log(print_r($dataSets, true));
        $this->set('dataSets', $dataSets);
        
    }
    
    public function typhoonPreparedness(){
        
        $this->layout = 'default';
        
    }
    
    public function typhoonClimatology(){
        
        $this->layout = 'default';
        
    }
    
    public function typhoonGlossary(){
        
        $this->layout = 'default';
        
    }
    
    public function about(){
        
        $this->layout = 'default';
        
    }
    
    public function impressum(){
        
        $this->layout = 'default';
        
    }
    
    
    
    
    
}