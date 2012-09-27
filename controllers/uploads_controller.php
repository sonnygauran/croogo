<?php

class UploadsController extends AppController{

    var $name = 'Uploads';
    
    public function beforeFilter() {
        parent::beforeFilter();
        
        if ($this->action == 'admin_index') {
            $this->Security->validatePost = false;
        }
        
        
    }
    
    function admin_video(){
        
        if(!empty($this->data)){
            $allowed_extensions = array('mp4', 'mov', 'wma', 'webm', 'm4v', 'avi');
            
            
            
            $date = date('YmdHis'); 
            $extension = explode('.', $this->data['Upload']['video']['name']);
            $extension = ($extension[count($extension)-1]);
            
            if(!in_array($extension, $allowed_extensions)){
                $this->Session->setflash('Invalid Extension');
                $this->redirect(array(
                        'plugin'=> null,
                        'controller' => 'uploads',
                        'action' => 'video'
                    
                ));
           
            }
            
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

   function admin_image(){
        if(!empty($this->data)){
            
            $allowed_extensions = array('jpg', 'gif', 'jpeg', 'png', 'bmp', 'svg');
            
            
            $date = date('YmdHis'); 
            $extension = explode('.', $this->data['Upload']['image']['name']);
            $extension = ($extension[count($extension)-1]);
            
            if (!in_array(strtolower($extension), $allowed_extensions)){
                $this->Session->setFlash('Invalid Extension');
                   $this->redirect(array(
                        'plugin'=> null,
                        'controller' => 'uploads',
                        'action' => 'image'
                    
                ));
                   
            }
            
            $tmp_name = $this->data['Upload']['image']['tmp_name'];
            
            
            $directory = Configure::read('Data.uploaded_images_folder');
            $destination =  $directory . $date.".".$extension;
            
            if(move_uploaded_file($tmp_name, $destination)){
                $this->Session->setFlash("Upload Successful!");
                $image = Configure::read('Data.uploaded_images') . "$date.$extension";
                $this->set(compact('image'));
            }else{
                $this->Session->setFlash("Something went wrong.");
            }
        }
         
   }
   
   function admin_view($file = ''){
       $location = WWW_ROOT . DS . 'uploads' . DS . 'uploaded_images';
       $files = scandir ($location);
       $excludes = array ('.', '..'); 
       $files = array_values(array_diff($files,$excludes));
       
       if(!empty($file)){
           if(unlink("$location/$file")){
                $this->Session->setFlash('File Successfully delted');
                $this->redirect(array(
                        'plugin' => null,
                        'controller' =>'uploads',
                        'action' => 'view'
                    ));
           }else{
                $this->Session->setFlash('Something went wrong');
           }
       } 
//       if (! empty ){
           
//       }
//       debug($files);
       
      $this->set(compact('files'));
   }
   
   function admin_weathertv($file ='') {
           $location = WWW_ROOT . DS . 'uploads' . DS . 'weathertv';
             $files = scandir ($location);
       $excludes = array ('.', '..'); 
       $files = array_values(array_diff($files,$excludes));
      if(!empty($file)){
           if(unlink("$location/$file")){
                $this->Session->setFlash('File Successfully delted');
                $this->redirect(array(
                        'plugin' => null,
                        'controller' =>'uploads',
                        'action' => 'weathertv'
                    ));
           }else{
                $this->Session->setFlash('Something went wrong');
           }
       }
         $this->set(compact('files'));     
   }
   function admin_success($file_name = ''){
       $url = Configure::read('Data.uploaded_videos') . $file_name;
       $width = "480px";
       $height = "320px";
       
       $this->set(compact('url', 'height', 'width'));
   }
   
   
}