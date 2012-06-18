<?php

class SearchController extends WeatherphAppController {
    
    public $name = 'Search';
    public $uses = array('Nima.NimaName');
    
    public function index($terms = '') {
        $this->log('INDEX!!!');
        
        
        if(!empty($this->params['pass'])){
            $keyword = $this->params['pass'][0];
            $this->paginate['NimaName'] = array (
                'conditions' => array(
                    'full_name_ro LIKE' => '%'.$keyword.'%'
                )
            );
        }
        if ($this->RequestHandler->isPost()) {
            $this->log('POST!!!');
            if (key_exists('terms', $_POST) AND strlen($_POST['terms']) <= 3) {
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
            $termStr = '/^([A-Za-z0-9]+)$/';
            
            if (preg_match($termStr, $terms)) {
                $this->log('MATCH!');
//                debug($this->data);
//                debug();
//                debug($_GET);

                
                $this->set('names',$this->paginate());
            } else {
                $this->log('NO MATCH!');
                $this->Session->setFlash(__('Invalid search term provided. Please check your search. You entered "'.$terms.'"', true), 'default', array('class' => 'error'));
                $this->redirect(Router::url('/'));
            }
            
            
        }
    }
    
    public function getResultCoordinates($keyword) {
        $this->layout = 'json/ajax';

        $NimaName = new NimaName();
        $keyword = $this->params['pass'][0];
        $locations = $NimaName->find('all', array('fields' => array('id' ,'lat', 'long', 'full_name_ro'),  'conditions' => array( 'full_name_ro LIKE' => '%'.$keyword.'%')));
        $this->set('locations', json_encode($locations));
    }
}