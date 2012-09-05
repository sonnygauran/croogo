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
            $date = date('YmdHis'); 
            $extension = explode('.', $this->data['Upload']['video']['name']);
            $extension = ($extension[count($extension)-1]);
            
            $tmp_name = $this->data['Upload']['video']['tmp_name'];
            
            $directory = WWW_ROOT . 'uploads' . DS . $this->data['Upload']['field'] . DS;
            $destination =  $directory . $date.".".$extension;
            
            if(!is_dir($directory)) mkdir ($directory); // create directory
            
            if(move_uploaded_file($tmp_name, $destination)){
                $this->Session->setFlash("Upload Successful!");
                
                if($this->data['Upload']['field'] == 'uploaded_videos'){
                    $this->redirect(array('action' => 'admin_success', $date));
                }
                
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