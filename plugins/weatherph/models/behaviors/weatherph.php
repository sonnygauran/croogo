<?php
/**
 * Weatherph Behavior
 *
 * Initial Weatherph behavior
 *
 * @category Behavior
 * @package  Weatherph
 * @author   Sonny Gauran <sgauran@meteomedia.com.ph>
 * @link     http://www.weather.com.ph
 */
class WeatherphBehavior extends ModelBehavior {
/**
 * Setup
 *
 * @param object $model
 * @param array  $config
 * @return void
 */
    public function setup(&$model, $config = array()) {
        if (is_string($config)) {
            $config = array($config);
        }

        $this->settings[$model->alias] = $config;
    }
/**
 * afterFind callback
 *
 * @param object  $model
 * @param array   $created
 * @param boolean $primary
 * @return array
 */
    public function afterFind(&$model, $results = array(), $primary = false) {
        if ($primary && isset($results[0][$model->alias])) {
            foreach ($results AS $i => $result) {
                if (isset($results[$i][$model->alias]['body'])) {
                    $results[$i][$model->alias]['body'] .= '<p>[Modified by WeatherphBehavior]</p>';
                }
            }
        } elseif (isset($results[$model->alias])) {
            if (isset($results[$model->alias]['body'])) {
                $results[$model->alias]['body'] .= '<p>[Modified by WeatherphBehavior]</p>';
            }
        }

        return $results;
    }

}
?>