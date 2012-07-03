<?php

class SearchController extends WeatherphAppController {
    
    public $name = 'Search';
    public $uses = array('Nima.NimaName');
    public $helpers = array('Cache');
    var $cacheAction = array(
        'index' => array('duration' => 86400),
        'getResultCoordinates' => array('duration' => 86400),
    );

    public function index($terms = '') {
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
                        "AND" => array(
                            "OR" => array(
                                'dsg' => "adm1",
                                'dsg' => "ppl",
                            )
                        ),
                        "OR" => array(
                            'full_name_ro' => "$keyword",
                            'full_name_ro LIKE' => "$keyword %",
                            'full_name_ro LIKE' => "% $keyword",
                        ),
                    ),
                    'order' => array(
                        'FIELD(NimaName.id, '.$idStrings.') DESC',
                        'FIELD(NimaName.dsg, "ADM1", "PPL") DESC'
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

        $gum = 'search.nima.name.coordinates';
        $locations = array();
        if (!Cache::read($gum, 'daily')) {
            
            $NimaName = new NimaName();
            $keyword = $this->params['pass'][0];
            $locations = $NimaName->find('all', array(
                    'limit' => 15,
                    'fields' => array('full_name_ro', 'long', 'lat', 'id'),
                    'conditions' => array(
                        "AND" => array(
                            "OR" => array(
                                'dsg' => "adm1",
                                'dsg' => "ppl",
                            )
                        ),
                        "OR" => array(
                            'full_name_ro' => "$keyword",
                            'full_name_ro LIKE' => "$keyword %",
                            'full_name_ro LIKE' => "% $keyword",
                        ),
                    ),
                ));
            
            Cache::write($gum, $locations, 'daily');
        } else {
            $locations = Cache::read($gum, 'daily');
        }
        
        $this->set('locations', json_encode($locations));
    }
}