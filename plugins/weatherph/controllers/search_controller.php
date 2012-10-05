<?php

class SearchController extends WeatherphAppController {
    
    public $name = 'Search';
    public $uses = array('Nima.NimaName', 'Nima.FipsCode', 'Nima.Area', 'Station');
    public $helpers = array('Cache', 'Javascript');
    var $cacheAction = array(
        'index' => array('duration' => 86400),
        'getResultCoordinates' => array('duration' => 86400),
    );

    public function index($terms = '') {
       $this->set('title_for_layout','Search for ' . ucwords($terms) );
    
        $this->log('INDEX!!!');
        $termStr = '/^([A-Za-z0-9 ]+)$/';
        
        if ($this->RequestHandler->isPost()) {
            if (key_exists('terms', $_POST) AND strlen($_POST['terms']) <= 3 AND preg_match($termStr, rawurldecode($terms))) {
                $this->log('TERMS INVALID!!!');
                $this->Session->setFlash(__('Please enter a search term longer than 3 letters', true), 'default', array('class' => 'error'));
                $this->redirect(Router::url('/'));
                exit;
            } else {
                $this->log('TERMS OK!!!');
                $this->redirect('/results/'.rawurlencode($_POST['terms']));
                exit;
            }
            
        } else {
            if (preg_match($termStr, rawurldecode($terms))) {
                $this->log('MATCH!');
//                debug($this->data);
//                debug();
//                debug($_GET);

                $keyword = $this->params['pass'][0];
                $keyword = rawurldecode($keyword);   

                $gum = 'search.nima.name';
                $names = array();
                
                $this->NimaName->Behaviors->attach('Containable');
                $nameIds   = Set::extract($names, '{n}.NimaName.id');
                $idStrings = implode(', ', $nameIds);

                $names = $this->NimaName->find('all', array(
                    'fields'=> 'NimaName.id',
                    'conditions' => array(
                        'AND' => array(
                            array(
                                'OR'=> array(
                                    array('full_name_ro Like' => "$keyword%"),
                                    array('full_name_ro Like' => "% $keyword"),
                                )
                            ),
                            array(
                                'OR' => array(
                                    array('dsg' => 'ppl'),
                                    array('dsg' => 'adm1'),
                                    array('dsg' => 'adm2'),
                                )
                            ),
                            array(
                                'nt' => 'N'
                            ),
                            array(
                                'type' => 2
                            )
                        ),
                        
                    ),
                ));
                
                $type = (count($names) > 0) ? 2 : 1;
                
                $names = $this->NimaName->find('all', array(
                    'conditions' => array(
                        'AND' => array(
                            array(
                                'OR'=> array(
                                    array('full_name_ro' => "$keyword"),
                                    array('full_name_ro Like' => "$keyword %"),
                                    array('full_name_ro Like' => "% $keyword"),
                                )
                            ),
                            array(
                                'OR' => array(
                                    array('dsg' => 'ppl'),
                                    array('dsg' => 'adm1'),
                                    array('dsg' => 'adm2'),
                                )
                            ),
                            array(
                                'nt' => 'N'
                            ),
                            array(
                                'type' => $type
                            )
                        ),
                        
                    ),
                    'contain' => array(
                        'FipsCode' => 'Area'
                    ),
                    'order' => array(
                        'FipsCode.type' => 'desc',
                        'NimaName.id' => 'desc',
                        "FIELD(NimaName.dsg, 'adm1', 'ppl')" => 'desc'
                    ),
                    'limit' => 11
                    
                )
                        
                );
                
                $this->paginate['NimaName'] = array(
                    'conditions' => array(
                        'AND' => array(
                            array(
                                'OR'=> array(
                                    array('full_name_ro' => "$keyword"),
                                    array('full_name_ro Like' => "$keyword %"),
                                    array('full_name_ro Like' => "% $keyword"),
                                )
                            ),
                            array(
                                'OR' => array(
                                    array('dsg' => 'ppl'),
                                    array('dsg' => 'adm1'),
                                    array('dsg' => 'adm2'),
                                )
                            ),
                            array(
                                'nt' => 'N'
                            ),
                            array(
                                'type' => $type
                            )
                        ),
                        
                    ),
                    'contain' => array(
                        'FipsCode' => 'Area'
                    ),
                    'order' => array(
                        'FipsCode.type' => 'desc',
                        'NimaName.id' => 'desc',
                        "FIELD(NimaName.dsg, 'adm1', 'ppl')" => 'desc'
                    ),
                    'limit' => 11
                    
                );
                $this->paginate['Station'] = array(
                    'conditions' => array(
                        'AND' => array(
                            array('name Like' => "%$keyword%"),
                            array('webaktiv !=' => 2),
                            array('webaktiv !=' => 0),
                            
                        ),
                            
                    ),
                );
                
//                $stations = $this->Station->find('all', array(
//                    'conditions' => array(
////                        'AND' => array(
////                            array(
//                                'name Like' => "%$keyword%",
////                                ),
////                            array('webaktiv !=' => 2),
////                            array('webaktiv !=' => 0),
//                        )
////                    )
//                ));
                
                
                
                $gum = 'search.'.rawurlencode($keyword)
                    .'.limit'.$this->paginate['limit']
                    .'.page'.$this->paginate['page'];
                $names = $this->paginate('NimaName');
                $stations = $this->paginate('Station');
                $count = count($names) + count($stations);
                $meta_for_description = $this->description('description', 'WeatherPhilippines');
        $meta_for_keywords = $this->keywords('keywords', 'WeatherPhilippines, 
            weather, philippines, weather philippines');
                
                $this->set(compact('names', 'stations', 'count', 'meta_for_description','meta_for_keywords'));
            } else {
                $this->log('NO MATCH!');
                $this->Session->setFlash(__('Invalid search term provided. Please check your search. You entered "'.$terms.'"', true), 'default', array('class' => 'error'));
                $this->redirect(Router::url('/'));
            }
            
            
        }
    }
    
    public function getResultCoordinates($keyword) {
        $this->layout = 'json/ajax';
        
        if($keyword){
            error_log("KEYOWRD: $keyword");
        }else{
            $url = $_SERVER['REQUEST_URI'];
            error_log("USL: $url");
            error_log(print_r($keyword, true));
        }
//        $query = "select `Name`.`id`, `Name`.`long`, `Name`.`lat`, `Name`.`full_name_ro`, `FipsCode`.name, `Region`.`name`, `Region`.`code`, `FipsCode`.`type` from `names` as `Name`, `fips_codes` as `FipsCode`, `regions` as `Region`  where ( `Name`.`fips_code_id` = `FipsCode`.`id` ) and ( `FipsCode`.`region_id` = `Region`.`id` ) and ( `Name`.`nt` = 'N' ) and ( `Name`.`dsg` = 'ppl'  or `Name`.`dsg` = 'adm1' or `Name`.`dsg` = 'adm2' ) and ( `Name`.`full_name_ro` = '$keyword' 	or `Name`.`full_name_ro` like '$keyword %'  or `Name`.`full_name_ro` like '% $keyword'  ) order by `FipsCode`.`type` desc, FIELD(`Region`.`code`, 'CAR', 'NCR') desc, `Name`.`id` desc, FIELD(`Name`.`dsg`, 'adm1', 'ppl') desc";
        $query = "select `Name`.`id`, `Name`.`long`, `Name`.`lat`, `Name`.`full_name_ro`, `FipsCode`.`type` from `names` as `Name`, `fips_codes` as `FipsCode`, `areas` as `Region`  where ( `Name`.`fips_code_id` = `FipsCode`.`id` ) and ( `FipsCode`.`area_id` = `Region`.`id` ) and ( `Name`.`nt` = 'N' ) and ( `Name`.`dsg` = 'ppl'  or `Name`.`dsg` = 'adm1' or `Name`.`dsg` = 'adm2' ) and ( `Name`.`full_name_ro` = '$keyword' 	or `Name`.`full_name_ro` like '$keyword %'  or `Name`.`full_name_ro` like '% $keyword'  ) order by `FipsCode`.`type` desc, FIELD(`Region`.`code`, 'CAR', 'NCR') desc, `Name`.`id` desc, FIELD(`Name`.`dsg`, 'adm1', 'ppl') desc";
//        $gum = 'search.nima.name.coordinates';
        $locations = array();
        $updated_results = array();
//        if (!Cache::read($gum, 'daily')) {
            
            $NimaName = new NimaName();
            
            $locations = $NimaName->query($query);
            $this->log($locations);
            $unfiltered_results = array();
        
        foreach ($locations as $value) {
            if($value['FipsCode']['type'] == CITY){
                $unfiltered_results[] = $value;
            }
        }
        
        if(empty($unfiltered_results)){
           $unfiltered_results = $locations; 
        }
        
        foreach ($unfiltered_results as $value){
            if(key_exists('FipsCode', $value)) unset($value['FipsCode']);
            $updated_results[] = $value;
        }
        
        $NimaName->useDbConfig = 'default';
        $sql = "select `Name`.`id`, `Name`.`name` as `full_name_ro`, `Name`.`lat`, `Name`.`lon` as `long`, `webaktiv` from `stations` as `Name` where (`Name`.`name` like '%$keyword%') and (webaktiv != 0 and webaktiv != 2)";
        $stations = $NimaName->query($sql);
        $updated_results = array_merge($updated_results, $stations);
        
//        debug($updated_results);
//        exit;
            
//            Cache::write($gum, $locations, 'daily');
//        } else {
//            $locations = Cache::read($gum, 'daily');
//        }
        $meta_for_description = $this->description('description', 'WeatherPhilippines');
        $meta_for_keywords = $this->keywords('keywords', 'WeatherPhilippines, 
            weather, philippines, weather philippines');
        $this->set(compact('meta_for_description','meta_for_description'));
        $this->set('locations', json_encode($updated_results));
    }
}