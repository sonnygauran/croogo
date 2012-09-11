<?php

class WeatherphAppController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        
	$cities = array( 'alaminos','angeles','antipolo','baguio','bais','balanga','batangas','bayawan','cadiz','cagayan_de_oro','calamba','caloocan','cauayan','cavite','dagupan','davao','digos','dipolog','gapan','iligan','iloilo','iriga','isabela','kabankalan','kidapawan','koronadal','laoag','ligao','lipa','lucena','makati','malaybalay','mandaue','masbate','muntinlupa','olongapo','pasay','puerto_princesa','samal','san_fernando','san_fernando_la_union','santa_rosa','santiago','silay','sorsogon','tacurong','tagum','tangub','toledo','tuguegarao','urdaneta','valenzuela','victorias','vigan','zamboanga');
    
	
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
                    $this->log('Client: '. $_SERVER['REMOTE_ADDR'] . ' tried to access ' . $_SERVER[ 'REQUEST_URI' ] . '', 'forbidden');
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
                    $this->log('Client: '. $_SERVER['REMOTE_ADDR'] . ' tried to access ' . $_SERVER[ 'REQUEST_URI' ] . '', 'forbidden');
//                    $this->redirect(array('plugin' => 'weatherph', 'controller' => 'weatherph', 'action' => 'index'));
                    $this->layout = 'default';
                }
                break;
            case 'getResultCoordinates':
                error_log("REFERERERER: " . $this->referer());
                if(!strstr($this->referer(), 'results')){
                    $this->log('Client: '. $_SERVER['REMOTE_ADDR'] . ' tried to access ' . $_SERVER[ 'REQUEST_URI' ] . '', 'forbidden');
                    exit;
                }
                break;
                           
                
        }

		$city = $cities[rand(0, count($cities))];
		$this->set(compact('city'));
    }
    
      
}
