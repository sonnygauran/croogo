<?php

class SearchController extends WeatherphAppController {
    
    public $name = 'Search';
    public $uses = array('Nima.NimaName');
    
    public function index($terms = '') {
        $this->log('INDEX!!!');
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
            
            //debug($this->paginate());
    //        if 
    //    
    //        $this->Session->setFlash(__('The Block has been saved', true), 'default', array('class' => 'success'));
    //				if (isset($this->params['form']['apply'])) {
    //					$this->redirect(array('action'=>'edit', $this->Block->id));
    //        
    //        $this->paginate['NimaName'] = array (
    //            'conditions' => array(
    //                'full_name_ro LIKE' => '%%'
    //            )
    //        );
            
        } else {
            $termStr = '/^([A-Za-z0-9]+)$/';
            
            if (preg_match($termStr, $terms)) {
                $this->log('MATCH!');
                debug($this->data);
                debug();
                debug($_GET);

                
                $this->set('names',$this->paginate());
            } else {
                $this->log('NO MATCH!');
                $this->Session->setFlash(__('Invalid search term provided. Please check your search. You entered "'.$terms.'"', true), 'default', array('class' => 'error'));
                $this->redirect(Router::url('/'));
            }
            
            
        }
    }
}