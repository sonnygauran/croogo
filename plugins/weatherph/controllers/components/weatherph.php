<?php
/**
 * Weatherph Component
 *
 * Initial Weatherph component
 *
 * @category Component
 * @package  Weatherph
 * @version  1.0
 * @author   Sonny Gauran <sgauran@meteomedia.com.ph>
 * @link     http://www.weather.com.ph
 */
class WeatherphComponent extends Object {
/**
 * Called after the Controller::beforeFilter() and before the controller action
 *
 * @param object $controller Controller with components to startup
 * @return void
 */
    public function startup(&$controller) {
        $controller->set('weatherphComponent', 'WeatherphComponent startup');
    }
/**
 * Called after the Controller::beforeRender(), after the view class is loaded, and before the
 * Controller::render()
 *
 * @param object $controller Controller with components to beforeRender
 * @return void
 */
    public function beforeRender(&$controller) {
    }
/**
 * Called after Controller::render() and before the output is printed to the browser.
 *
 * @param object $controller Controller with components to shutdown
 * @return void
 */
    public function shutdown(&$controller) {
    }
    
}
?>