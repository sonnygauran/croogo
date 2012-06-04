<?php

/**
 * Typhoon Controller
 *
 * @category Controller
 * @package  Weatherph
 * @version  1.0
 * @author   Sonny Gauran <sgauran@meteomedia.com.ph>
 * @link     http://www.weather.com.ph
 */
class TyphoonController extends WeatherphAppController {

    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Typhoon';
    public $uses = array('Block');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'default';
    }
    
    public function preparedness() {
        
    }

    public function climatology() {
        
    }

    public function glossary() {

    }

}