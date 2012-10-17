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
        $meta_for_description = $this->description('description', 'WeatherPhilippines');
           $og_image = array('property'=>'og:image','content'=>'http://alpha.weather.com.ph/theme/weatherph/img/logo.png');
        $og_title = array('property'=>'og:title','content'=>'Weather Philippines Foundation');
        $this->set(compact('meta_for_description','og_image','og_title'));

        
    }

    public function climatology() {
        $meta_for_description = $this->description('description', 'WeatherPhilippines');
           $og_image = array('property'=>'og:image','content'=>'http://alpha.weather.com.ph/theme/weatherph/img/logo.png');
        $og_title = array('property'=>'og:title','content'=>'Weather Philippines Foundation');
        $this->set(compact('meta_for_description','og_image','og_title'));
        
    }

}