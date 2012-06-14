<?php
class NscbSummariesController extends AppController {

	var $name = 'NscbSummaries';

	function index() {
		$this->NscbSummary->recursive = 0;
		$this->set('nscbSummaries', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid nscb summary', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('nscbSummary', $this->NscbSummary->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->NscbSummary->create();
			if ($this->NscbSummary->save($this->data)) {
				$this->Session->setFlash(__('The nscb summary has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The nscb summary could not be saved. Please, try again.', true));
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid nscb summary', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->NscbSummary->save($this->data)) {
				$this->Session->setFlash(__('The nscb summary has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The nscb summary could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->NscbSummary->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for nscb summary', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->NscbSummary->delete($id)) {
			$this->Session->setFlash(__('Nscb summary deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Nscb summary was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
