<?php

class GnsNames extends WeatherphAppController {

    public $name = 'GnsNames';

    /**
     * index method
     *
     * @return void
     */
    public function index() {
        $this->Names->recursive = 0;
        $this->set('names', $this->paginate());
    }

    /**
     * view method
     *
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        $this->Names->id = $id;
        if (!$this->Names->exists()) {
            throw new NotFoundException(__('Invalid name'));
        }
        $this->set('name', $this->Names->read(null, $id));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            $this->Names->create();
            if ($this->Names->save($this->request->data)) {
                $this->Session->setFlash(__('The name has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The name could not be saved. Please, try again.'));
            }
        }
    }

    /**
     * edit method
     *
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        $this->Names->id = $id;
        if (!$this->Names->exists()) {
            throw new NotFoundException(__('Invalid name'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Names->save($this->request->data)) {
                $this->Session->setFlash(__('The name has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The name could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->Names->read(null, $id);
        }
    }

    /**
     * delete method
     *
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Names->id = $id;
        if (!$this->Names->exists()) {
            throw new NotFoundException(__('Invalid name'));
        }
        if ($this->Names->delete()) {
            $this->Session->setFlash(__('Names deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Names was not deleted'));
        $this->redirect(array('action' => 'index'));
    }

}