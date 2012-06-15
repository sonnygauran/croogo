<?php

require '/home/luis/Desktop/assign/phpQuery.git/phpQuery/phpQuery.php';
require '/home/luis/Desktop/assign/provinces.php';
require '/home/luis/Desktop/assign/cities.php';
require '/home/luis/Desktop/assign/municipalities.php';

class CityShell extends Shell {

    public $uses = array('City');

    function main() {
        $NscbCity = new NscbCity();
        $NscbCity->init();
        $cities = $NscbCity->get();
        App::import('Model', 'Province');
        $Province = new Province();
        
        foreach ($cities as $city) {
            $this->City->create();
            
            $prefix = substr($city['code'], 0, 4);
            
            $province = $Province->find('first', array('conditions' => array(
                'Province.code LIKE' => $prefix.'%',
            )));
            
            $province_id = 0;
            if (!empty($province)) {
                $province_id = $province['Province']['id'];
            }
            
            $data = array(
                'name' => $city['name'],
                'code' => $city['code'],
                'income_class' => $city['income_class'],
                'voters' => $city['voters'],
                'land_area' => $city['land_area'],
                'province_id' => $province_id
            );
            echo print_r($data, true)."\n";
            $this->City->save($data);
        }
        
        
    }

}

