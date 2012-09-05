<?php

class SearchController extends WeatherphAppController {
    
    public $name = 'Search';
    public $uses = array('Nima.NimaName', 'Nima.FipsCode', 'Nima.Region');
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
                if (!Cache::read($gum, 'daily')) {
                    $names = $this->NimaName->find('all', array(
                        'conditions' => array(
                            'full_name_ro LIKE' => "%city%"
                        ),
                    ));
                    
                    Cache::write($gum, $names, 'daily');
                } else {
                    $names = Cache::read($gum, 'daily');
                }
                
                $nameIds   = Set::extract($names, '{n}.NimaName.id');
                $idStrings = implode(', ', $nameIds);

                
                $this->paginate['NimaName'] = array(
                    'limit' => 15,
                    'fields' => array('id', 'full_name_ro'),
                    'conditions' => array(
                        'keyword' => $keyword,
                            )
                );
                $gum = 'search.'.rawurlencode($keyword)
                    .'.limit'.$this->paginate['limit']
                    .'.page'.$this->paginate['page'];
                
                
                $names = $this->paginate();
                
                
                $this->set(compact('names'));
            } else {
                $this->log('NO MATCH!');
                $this->Session->setFlash(__('Invalid search term provided. Please check your search. You entered "'.$terms.'"', true), 'default', array('class' => 'error'));
                $this->redirect(Router::url('/'));
            }
            
            
        }
    }
    
    public function getResultCoordinates($keyword) {
        $this->layout = 'json/ajax';
        
//        $query = "select `Name`.`id`, `Name`.`long`, `Name`.`lat`, `Name`.`full_name_ro`, `FipsCode`.name, `Region`.`name`, `Region`.`code`, `FipsCode`.`type` from `names` as `Name`, `fips_codes` as `FipsCode`, `regions` as `Region`  where ( `Name`.`fips_code_id` = `FipsCode`.`id` ) and ( `FipsCode`.`region_id` = `Region`.`id` ) and ( `Name`.`nt` = 'N' ) and ( `Name`.`dsg` = 'ppl'  or `Name`.`dsg` = 'adm1' or `Name`.`dsg` = 'adm2' ) and ( `Name`.`full_name_ro` = '$keyword' 	or `Name`.`full_name_ro` like '$keyword %'  or `Name`.`full_name_ro` like '% $keyword'  ) order by `FipsCode`.`type` desc, FIELD(`Region`.`code`, 'CAR', 'NCR') desc, `Name`.`id` desc, FIELD(`Name`.`dsg`, 'adm1', 'ppl') desc";
        $query = "select `Name`.`id`, `Name`.`long`, `Name`.`lat`, `Name`.`full_name_ro`, `FipsCode`.`type` from `names` as `Name`, `fips_codes` as `FipsCode`, `regions` as `Region`  where ( `Name`.`fips_code_id` = `FipsCode`.`id` ) and ( `FipsCode`.`region_id` = `Region`.`id` ) and ( `Name`.`nt` = 'N' ) and ( `Name`.`dsg` = 'ppl'  or `Name`.`dsg` = 'adm1' or `Name`.`dsg` = 'adm2' ) and ( `Name`.`full_name_ro` = '$keyword' 	or `Name`.`full_name_ro` like '$keyword %'  or `Name`.`full_name_ro` like '% $keyword'  ) order by `FipsCode`.`type` desc, FIELD(`Region`.`code`, 'CAR', 'NCR') desc, `Name`.`id` desc, FIELD(`Name`.`dsg`, 'adm1', 'ppl') desc";
//        $gum = 'search.nima.name.coordinates';
        $locations = array();
        $updated_results = array();
//        if (!Cache::read($gum, 'daily')) {
            
            $NimaName = new NimaName();
            $keyword = $this->params['pass'][0];
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
        $sql = "select `Name`.`id`, `Name`.`name` as `full_name_ro`, `Name`.`lat`, `Name`.`lon` as `long` from `stations` as `Name` where (`Name`.`name` like '%$keyword%')";
        $stations = $NimaName->query($sql);
        $updated_results = array_merge($updated_results, $stations);
//        debug($updated_results);
//        exit;
            
//            Cache::write($gum, $locations, 'daily');
//        } else {
//            $locations = Cache::read($gum, 'daily');
//        }
        
        $this->set('locations', json_encode($updated_results));
    }
}