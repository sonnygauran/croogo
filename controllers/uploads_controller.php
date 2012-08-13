<?php

class UploadsController extends AppController{

    var $name = 'Uploads';
    
    public function beforeFilter() {
        parent::beforeFilter();
        
        if ($this->action == 'admin_index') {
            $this->Security->validatePost = false;
        }
        
        
    }
    
    function admin_index(){
        
        if(!empty($this->data)){            
            $tmp_name = $this->data['Upload']['video']['tmp_name'];
            $destination = WWW_ROOT . DS . 'uploads' . DS . 'weathertv' . DS . $this->data['Upload']['video']['name'];
            if(move_uploaded_file($tmp_name, $destination)){
                $this->Session->setFlash("Upload Successful!");
                $this->redirect(array('action' => 'admin_success'));
            }
        }
         
   }
   
   function admin_success(){
       $url = "http://";
       $width = "480px";
       $height = "320px";
       
       $this->set(compact('url', 'height', 'width'));
   }
   
   
}