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
    

    public function beforeFilter(){
        date_default_timezone_set('UTC');
        parent::beforeFilter();
    }

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
    public function admin_getTwoWeekReadings(){
        //$this->layout = 'json/ajax';
        
        App::import('Model', 'Weatherph.WeatherphStationReading');
        
        $WeatherphStationReading = new WeatherphStationReading();
        $reading = $WeatherphStationReading->get('all', array('conditions' => array(
            'days_target' => 4,
        )));
 //       Configure::write('debug', 0);
        debug($reading);
        
    }
}