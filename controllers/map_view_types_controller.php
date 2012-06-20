<?php
class MapViewTypesController extends AppController {

	var $name = 'MapViewTypes';

	function index() {
		$this->MapViewType->recursive = 0;
		$this->set('mapViewTypes', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid map view type', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('mapViewType', $this->MapViewType->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->MapViewType->create();
			if ($this->MapViewType->save($this->data)) {
				$this->Session->setFlash(__('The map view type has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The map view type could not be saved. Please, try again.', true));
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid map view type', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->MapViewType->save($this->data)) {
				$this->Session->setFlash(__('The map view type has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The map view type could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->MapViewType->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for map view type', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->MapViewType->delete($id)) {
			$this->Session->setFlash(__('Map view type deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Map view type was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
