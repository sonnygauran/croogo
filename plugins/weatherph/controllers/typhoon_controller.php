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
        $meta_for_description = $this->description('description', "What To Do Before A Typhoon - Weather Philippines Foundation");
        $og_image = array('property'=>'og:image','content'=>'http://alpha.weather.com.ph/theme/weatherph/img/logo.png');
        $og_title = array('property'=>'og:title','content'=>'Typhoon Preparedness | Weather Philippines Foundation');
        $og_description = array('property'=>'og:description','content'=>'What to do before a typhoon - Weather Philippines Foundation');
        $this->set(compact('meta_for_description','og_image','og_title','og_description'));

        
    }

    public function climatology() {
        $meta_for_description = $this->description('description', "Typhoon Climatology - Weather Philippines Foundation");
        $og_image = array('property'=>'og:image','content'=>'http://alpha.weather.com.ph/theme/weatherph/img/logo.png');
        $og_title = array('property'=>'og:title','content'=>'Climatology | Weather Philippines Foundation');
        $og_description = array('property'=>'og:description','content'=>'Typhoon Climatology - Weather Philippines Foundation');
        $this->set(compact('meta_for_description','og_image','og_title','og_description'));
        
    }

}