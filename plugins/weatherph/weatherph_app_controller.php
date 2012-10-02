<?php

class WeatherphAppController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Node->Behaviors->attach('Containable');
        $show_alert = false;

        $allowed_referrers = array();


        error_log("WEBROOT: " . $this->webroot);
        switch($this->action){
            case 'getDetailedForecast':
                $allowed_referrers = array(
                    $this->webroot . 'view/', // Linux Hosts
                    $this->webroot . 'swf/AnyChart.swf' // OS X referrer
                );

                $this->log('Referer: ' . $this->referer());
                if(!in_array($this->referer(), $allowed_referrers)){
                    $this->log('Client: '. $_SERVER['REMOTE_ADDR'] . ' tried to access ' . $_SERVER[ 'REQUEST_URI' ] . '', 'FORBIDDEN');
                    exit;
                }else{
                    $this->log('allowed');
                }
                break;
                case 'getStations':
                $allowed_referrers = array(
                    $this->webroot, // Linux Hosts
                );
//
                if(!in_array($this->referer(), $allowed_referrers)){
                    $this->log('Client: '. $_SERVER['REMOTE_ADDR'] . ' tried to access ' . $_SERVER[ 'REQUEST_URI' ] . '', 'FORBIDDEN');
//                    $this->redirect(array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'index'));
                    $this->layout = 'default';
                }
                break;
            case 'getResultCoordinates':
                error_log("REFERERERER: " . $this->referer());
                if(!strstr($this->referer(), 'results')){
                    $this->log('Client: '. $_SERVER['REMOTE_ADDR'] . ' tried to access ' . $_SERVER[ 'REQUEST_URI' ] . '', 'FORBIDDEN');
                    exit;
                }
                break;


        }


    }
}