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
        $this->view = 'Media';
        
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
            $isKarten = ($_SERVER['HTTP_HOST'] == 'karten.meteomedia.ch');
            
            if ($parts[0] === 'theme' OR $isKarten) {
                $path = array();
                if ($isKarten) {
                        $path[] = $parts[3];
                        $path[] = $parts[4];
                        $path[] = $parts[5];
                        $path[] = $parts[6];
                        $parts = $path;
                }
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
                    'mimetype' => array(
                        'webm' => 'video/'.$ext,
                    ),
                    'path' => $assetFile
                );

                $this->set($options);

                App::import('View', 'Media', false);
                $controller = $this;
                $contentType = '';

                $Media = new MediaView($controller);
                if (isset($Media->mimeType[$ext])) {
                    $contentType = $Media->mimeType[$ext];
                } else {
                    if (in_array($ext, array('webm','mp4'))) {
                        $contentType = 'video/'.$ext;
                        header('Accept-Ranges: bytes');
                        header('Keep-Alive: timeout=3, max=100');
                    } else {
                        $contentType = 'application/octet-stream';
                        $agent = env('HTTP_USER_AGENT');
                        if (preg_match('%Opera(/| )([0-9].[0-9]{1,2})%', $agent) || preg_match('/MSIE ([0-9].[0-9]{1,2})/', $agent)) {
                            $contentType = 'application/octetstream';
                        }
                    }
                }
//                $this->log(compact('contentType','ext'));
                header("Date: " . date("D, j M Y G:i:s ", filemtime($assetFile)) . 'GMT');
                header('Content-type: ' . $contentType);
//                header("Expires: " . gmdate("D, j M Y H:i:s", time() + DAY) . " GMT");
//                header("Cache-Control: cache");
//                //header("Cache-Length: ".  filesize($assetFile));
                header("Content-Length: ". (string) filesize($assetFile));
//                header("Pragma: cache");

                readfile($assetFile);
                exit;
            } else {
                throw new Exception('Cannot download file');
            }
        }
    }
}
