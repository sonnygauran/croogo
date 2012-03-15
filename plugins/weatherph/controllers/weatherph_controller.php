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
        parent::beforeFilter();
    }

    public function admin_index() {
        $this->set('title_for_layout', __('Weatherph', true));
    }

    public function index() {
        $this->set('title_for_layout', __('Weatherph', true));
        $this->set('weatherphVariable', 'value here');
    }
    
    public function getStations(){
        $this->layout = 'json/ajax';

        $this->set('title_for_layout', __('Weatherph', true));
        App::import('Model', 'Weatherph.WeatherphStation');
        
        $WeatherphStation = new WeatherphStation();
        $stations = $WeatherphStation->find('all');

        $this->set('stations', json_encode($stations));
    }

}