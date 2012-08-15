<?php


class AssetsController extends AppController {

    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Assets';
    public $uses = array('Block','Link');

    public function beforeFilter() {
        parent::beforeFilter();
        
        $this->params['requested'] = TRUE;
        $this->params['admin'] = TRUE;
    }
    
    public function theme() {
        $this->view = 'Streaming';
        
        $url = Router::url();
        
        $ext = array_pop(explode('.', $url));
		$parts = explode('/', $url);
        
        if (trim(reset($parts)) == '') {
            unset($parts[0]);
            $parts = array_values($parts);
        }
        
        if (reset($parts) == 'index.php') {
            unset($parts[0]);
            $parts = array_values($parts);
        }
        
        if (reset($parts) == 'assets') {
            unset($parts[0]);
            $parts = array_values($parts);
        }
        
        if (count($parts) < 4) {
            $this->Session->setFlash(__('Invalid Asset', true), 'default', array('class' => 'error'));
			$this->redirect(array('controller' => 'weatherph','action'=>'index'));
        } else {
            $assetFile = null;
            //print_r($_SERVER);
            $this->log(print_r($parts, true));
            
            if ($parts[0] === 'theme') {
                $path = array();
                
                $themeName = $parts[1];
                unset($parts[0], $parts[1]);
                
                $fileFragment = array();
                $fileFramentStart = FALSE;
                foreach ($parts as $part) {
                    if ($part == 'webroot') {
                        $fileFramentStart = TRUE;
                    }
                    
                    if ($fileFramentStart and ($part != 'webroot')) {
                        $fileFragment[] = $part;
                    }
                }
                
                $assetPath = $fileFragment;
                array_pop($assetPath);
                $fileFragment = implode(DS, $fileFragment);

                $path = App::themePath($themeName) . 'webroot' . DS;
//                $this->log(compact('path','fileFragment'));
                if (file_exists($path . $fileFragment)) {
                    $assetFile = $path . $fileFragment;
                }
//                $this->log(compact('assetFile'));
                $options = array(
                    'id' => end($parts),
                    'name' => reset(explode('.', end($parts))),
                    'download' => false,
                    'extension' => $ext,
                    'cache' => false,
                    'mimeType' => array(
                        $ext => 'video/'.$ext,
                    ),
                    'path' => $path.implode(DS, $assetPath).DS
                );

                $this->set($options);
            } else {
                throw new Exception('Cannot download file');
            }
        }
    }
}
