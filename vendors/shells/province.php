<?php

require '/home/luis/Desktop/assign/phpQuery.git/phpQuery/phpQuery.php';
require '/home/luis/Desktop/assign/regions.php';
require '/home/luis/Desktop/assign/provinces.php';
require '/home/luis/Desktop/assign/cities.php';
require '/home/luis/Desktop/assign/municipalities.php';

class ProvinceShell extends Shell {

    public $uses = array('Province');

    function main() {
        $NscbRegion = new NscbRegion();
        $regions = $NscbRegion->get();
        $NscbProvince = new NscbProvince();
        $NscbProvince->setRegions($regions);
        $NscbProvince->init();
        $provinces = $NscbProvince->get();
                    echo "<<<";
        echo print_r($provinces, true);
                    echo ">>>";
        App::import('Model', 'Region');
        $Region = new Region();
        
        foreach ($provinces as $provinceKey => $provinceData) {
            if (!empty($provinceData)) {

            $region = $Region->findByShortName($provinceKey);

            
                foreach ($provinceData['data'] as $provinceCode => $provinceDetails) {
                    $saveData = array(
                        'name' => $provinceDetails['name'],
                        'code' => $provinceDetails['code'],
                        'income_class' => $provinceDetails['income_class'],
                        'voters' => $provinceDetails['voters'],
                        'population' => $provinceDetails['population'],
                        'land_area' => $provinceDetails['land_area'],
                        'region_id' => $region['Region']['id'],
                    );
                    $this->Province->create();
                    $this->Province->save($saveData);
                }
            }
        }
    }

}

