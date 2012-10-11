<?php

class MediaController extends AppController {

    var $name = 'Media';

    public function beforeFilter() {
        parent::beforeFilter();

        if ($this->action == 'admin_index') {
            $this->Security->validatePost = false;
        }
    }

    function admin_video($file="") {

        if (!empty($this->data)) {
            $allowed_extensions = array('mp4', 'mov', 'wma', 'webm', 'm4v', 'avi');
	            
			$date = date('YmdHis');
            $extension = explode('.', $this->data['Media']['video']['name']);
            $extension = ($extension[count($extension) - 1]);

            if (!in_array($extension, $allowed_extensions)) {
                $this->Session->setflash('Invalid Extension');
                $this->redirect(array(
                    'plugin' => null,
                    'controller' => 'media',
                    'action' => 'video'
                ));
            }

            $tmp_name = $this->data['Media']['video']['tmp_name'];

            $directory = WWW_ROOT . 'uploads' . DS . $this->data['Media']['type'] . DS;
            $destination = $directory . $date . "." . $extension;
			$this->data['Media']['name'] = $date;

            if (!is_dir($directory))
                mkdir($directory); // create directory

            if (move_uploaded_file($tmp_name, $destination) && $this->Media->save($this->data)) {
                $this->Session->setFlash("Upload Successful!");

                if ($this->data['Media']['type'] == 'uploaded_videos') {
                    $this->redirect(array('action' => 'admin_success', $date));
                }
            } else {
                $this->Session->setFlash("Something went wrong.");
            }
        }
    }

    function admin_image($file = '') {
        
		if (!empty($this->data)) {
            $allowed_extensions = array('jpg', 'gif', 'jpeg', 'png', 'bmp', 'svg');

			$this->data['Media']['type'] = 'image';
            $date = date('YmdHis');
            $extension = explode('.', $this->data['Media']['image']['name']);
            $extension = ($extension[count($extension) - 1]);

            if (!in_array(strtolower($extension), $allowed_extensions)) {
                $this->Session->setFlash('Invalid Extension');
                $this->redirect(array(
                    'plugin' => null,
                    'controller' => 'media',
                    'action' => 'image'
                ));
            }

            $tmp_name = $this->data['Media']['image']['tmp_name'];

            $directory = Configure::read('Data.uploaded_images_folder');
            $destination = $directory . $date . "." . $extension;
			$this->data['Media']['name'] = "$date.$extension";

            if (move_uploaded_file($tmp_name, $destination) && $this->Media->save($this->data)) {
                $this->Session->setFlash('Upload Successful!');
                $image = Configure::read('Data.uploaded_images') . "$date.$extension";
                $this->set(compact('image'));
            } else {
                $this->Session->setFlash("Something went wrong.");
            }
        }
    }

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Block', true), 'default', array('class' => 'error'));
			$this->redirect(array('action'=>'index'));
		}
		if (!isset($this->params['named']['token']) || ($this->params['named']['token'] != $this->params['_Token']['key'])) {
			$blackHoleCallback = $this->Security->blackHoleCallback;
			$this->$blackHoleCallback();
		}
		if ($this->Media->delete($id)) {
			$this->Session->setFlash(__('Media deleted', true), 'default', array('class' => 'success'));
			$this->redirect(array('action'=>'manage'));
		}
	}

	function admin_manage(){
		$weathertv_videos = $this->Media->find('all', array(
			'conditions' => array(
				'type' => 'weathertv'
			)
		));
		$blog_videos = $this->Media->find('all', array(
			'conditions' => array(
				'type' => 'uploaded_videos'
			)
		));

		$images = $this->Media->find('all', array(
			'conditions' => array(
				'type' => 'image'
			)
		));

		$this->set(compact('images', 'blog_videos', 'weathertv_videos'));
	}

    function admin_success($file_name = '') {
        $url = Configure::read('Data.uploaded_videos') . $file_name;
        $width = "480px";
        $height = "320px";

        $this->set(compact('url', 'height', 'width'));
    }


}
