<?php
class MapViewsController extends AppController {

	var $name = 'MapViews';

	function index() {
		$this->MapView->recursive = 0;
		$this->set('mapViews', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid map view', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('mapView', $this->MapView->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->MapView->create();
			if ($this->MapView->save($this->data)) {
				$this->Session->setFlash(__('The map view has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The map view could not be saved. Please, try again.', true));
			}
		}
		$mapViewTypes = $this->MapView->MapViewType->find('list');
		$this->set(compact('mapViewTypes'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid map view', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->MapView->save($this->data)) {
				$this->Session->setFlash(__('The map view has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The map view could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->MapView->read(null, $id);
		}
		$mapViewTypes = $this->MapView->MapViewType->find('list');
		$this->set(compact('mapViewTypes'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for map view', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->MapView->delete($id)) {
			$this->Session->setFlash(__('Map view deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Map view was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
