<?php
	require '/home/luis/Desktop/assign/phpQuery.git/phpQuery/phpQuery.php';
	require '/home/luis/Desktop/assign/regions.php';
    require '/home/luis/Desktop/assign/provinces.php';
    require '/home/luis/Desktop/assign/cities.php';
    require '/home/luis/Desktop/assign/municipalities.php';

class RegionShell extends Shell {
        public $uses = array('Region');
             function main(){
            $NscbRegion = new NscbRegion();
            $regions = $NscbRegion->get();
              print_r($regions);
			
                 foreach ($regions as $region) {
                      $this->Region->create();
                         $this->Region->save(array(
                          'name' => $region['name'],
                          'code' => $region['code'],
                          'short_name' => $region['short_name'],
                     ));
        }
    }
}
