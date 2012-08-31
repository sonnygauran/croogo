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
            $directory = WWW_ROOT . DS . 'uploads' . DS . 'uploaded_videos' . DS;
            $destination =  $directory . $this->data['Upload']['video']['name'];
            
            if(!is_dir($directory)) mkdir ($directory); // create directory
            
            if(move_uploaded_file($tmp_name, $destination)){
                $this->Session->setFlash("Upload Successful!");
                $file_name = explode('.', $this->data['Upload']['video']['name']);
                unset($file_name[count($file_name)-1]);
                $file_name = implode('.', $file_name);
                $this->redirect(array('action' => 'admin_success', $file_name));
            }else{
                $this->Session->setFlash("Something went wrong.");
            }
        }
         
   }
   
   function admin_success($file_name = ''){
       $url = Configure::read('Data.uploaded_videos') . $file_name;
       $width = "480px";
       $height = "320px";
       
       $this->set(compact('url', 'height', 'width'));
   }
   
   
}