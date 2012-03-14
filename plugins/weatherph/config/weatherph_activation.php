<?php
/**
 * Weatherph Activation
 *
 * Activation class for Weatherph plugin.
 *
 * @package  Weatherph
 * @author   Sonny Gauran <sgauran@meteomedia.com.ph>
 * @link     http://www.weather.com.ph
 */
class WeatherphActivation {

/**
 * onActivate will be called if this returns true
 *
 * @param  object $controller Controller
 * @return boolean
 */
    public function beforeActivation(&$controller) {
        return true;
    }
/**
 * Called after activating the plugin in WeatherphPluginsController::admin_toggle()
 *
 * @param object $controller Controller
 * @return void
 */
    public function onActivation(&$controller) {
        // ACL: set ACOs with permissions
        $controller->Croogo->addAco('Weatherph'); // WeatherphController
        $controller->Croogo->addAco('Weatherph/admin_index'); // WeatherphController::admin_index()
        $controller->Croogo->addAco('Weatherph/index', array('registered', 'public')); // WeatherphController::index()

        // Main menu: add an Example link
        $mainMenu = $controller->Link->Menu->findByAlias('main');
        $controller->Link->Behaviors->attach('Tree', array(
            'scope' => array(
                'Link.menu_id' => $mainMenu['Menu']['id'],
            ),
        ));
        $controller->Link->save(array(
            'menu_id' => $mainMenu['Menu']['id'],
            'title' => 'Example',
            'link' => 'plugin:weatherph/controller:weatherph/action:index',
            'status' => 1,
        ));
    }
/**
 * onDeactivate will be called if this returns true
 *
 * @param  object $controller Controller
 * @return boolean
 */
    public function beforeDeactivation(&$controller) {
        return true;
    }
/**
 * Called after deactivating the plugin in WeatherphPluginsController::admin_toggle()
 *
 * @param object $controller Controller
 * @return void
 */
    public function onDeactivation(&$controller) {
        // ACL: remove ACOs with permissions
        $controller->Croogo->removeAco('Weatherph'); // WeatherphController ACO and it's actions will be removed

        // Main menu: delete Weatherph link
        $link = $controller->Link->find('first', array(
            'conditions' => array(
                'Menu.alias' => 'main',
                'Link.link' => 'plugin:weatherph/controller:weatherph/action:index',
            ),
        ));
        $controller->Link->Behaviors->attach('Tree', array(
            'scope' => array(
                'Link.menu_id' => $link['Link']['menu_id'],
            ),
        ));
        if (isset($link['Link']['id'])) {
            $controller->Link->delete($link['Link']['id']);
        }
    }
}
?>