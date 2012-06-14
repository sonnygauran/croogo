<?php

	require '/home/luis/Desktop/assign/phpQuery.git/phpQuery/phpQuery.php';
	require '/home/luis/Desktop/assign/regions.php';
    require '/home/luis/Desktop/assign/provinces.php';
    require '/home/luis/Desktop/assign/cities.php';
    require '/home/luis/Desktop/assign/municipalities.php';

class MunicipalityShell extends Shell {
        public $uses = array('Municipality','Summary');
             function main(){
            $NscbMunicipality = new NscbMunicipality();
            $NscbMunicipality->init();
            $municipalities = $NscbMunicipality->get();
			App::import('Model','Province');
			$Province = new Province();

			foreach ($municipalities as $municipality){
				$this->Municipality->create();
				$prefix = substr($municipality['code'], 0, 4);
				$province = $Province->find('first', array('conditions' => array(
					'Province.code LIKE' => $prefix.'%',
				)));
				
				$province_id = 0;
				if (!empty($province)){
					$province_id = $province['Province']['id'];
				}
				
				$data = array(
					'name' => $municipality['name'],
					'code' => $municipality['code'],
					'income_class' => $municipality['income_class'],
					'voters' => $municipality['voters'],
					'population' => $municipality['population'],
					'land_area' => $municipality['land_area'],
					'province_id' => $province_id
				);
				echo print_r($data, true)."\n";
				$this->Municipality->save($data);

			}
		}
}
