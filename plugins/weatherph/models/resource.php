<?php

class Resource extends WeatherphAppModel {
    public static $resourceTypes = array();
    
    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        
        if (empty(self::$resourceTypes)) {
            App::import('Model', 'Weatherph.ResourceType');
            $Type = new ResourceType();
            $types = $Type->find('all');
            $types = Set::combine($types, '{n}.ResourceType.name', '{n}.ResourceType.id');
            self::$resourceTypes = $types;
        }
        
    }
    
    public function generateKey($type, $name, $value) {
        $id = NULL;
        while($id == NULL) {
            $id = $this->makeHash($type, $name);
        }
        
        $Resource = new Resource();
        $Resource->read(null, $id);
        $data = $Resource->data;
        
        if ($Resource->save(compact('value'))) {
            return $data['Resource']['hash'];
        }
        return FALSE;
    }
    
    public function makeHash($type, $name) {
        $hash = hash('sha512', self::$resourceTypes[$type].rand(0, 9999).$name.rand(0, 9999).time());
        $count = $this->find('all', array('conditions'=>array(
            'resource_type_id' => self::$resourceTypes[$type],
            'name'             => $name,
            'hash'             => $hash,
        )));
        if (count($count) == 0) {
            $Resource = new Resource();
            $Resource->create();
            $data = array(
                'resource_type_id' => self::$resourceTypes[$type],
                'name'             => $name,
                'hash'             => $hash,
            );
            if ($Resource->save($data)) {
                return $Resource->id;
            }
        }
        return FALSE;
    }
    
    public function obtain($type, $name, $hash) {
        $type = str_replace('_', '-', $type);
        $this->log(print_r(func_get_args(), true));
        if (!in_array($type, array_keys(self::$resourceTypes))) {
            return FALSE;
        }
        
        $resources = $this->find('all', array('conditions'=>array(
            'resource_type_id' => self::$resourceTypes[$type],
            'name' => $name,
            'hash' => $hash,
            'is_read' => NULL,
        )));
        $this->log('a   ~>'.print_r(compact('resources'), true));
        
        if (count($resources) == 1) {
            $resource = reset($resources);
            $this->id = $resource['Resource']['id'];
            $this->saveField('is_read', TRUE);

            return $resource;
        } else if (count($resources) > 1) {
            throw new Exception('Duplicate resource exception');
            return FALSE;
        } else {
            // Assumed to be 0 or below
            return FALSE;
        }
    }
    
}