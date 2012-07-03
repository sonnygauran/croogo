<?php

class NimaName extends NimaAppModel {

    public $name = 'Name';
    public $useDbConfig = 'nima';

//    function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
//        if (!empty($extra['contain'])) {
//            $contain = $extra['contain'];
//        }
//
//        $uniqueCacheId = $this->getGum(func_get_args());
//        
//        $pagination = Cache::read('pagination-' . $this->alias . '-' . $uniqueCacheId, 'daily');
//        if (empty($pagination)) {
//            $pagination = $this->find('all', compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive', 'group', 'contain'));
//            Cache::write('pagination-' . $this->alias . '-' . $uniqueCacheId, $pagination, 'daily');
//        }
//        return $pagination;
//    }
//
//    function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
//        if (!empty($extra['contain'])) {
//            $contain = $extra['contain'];
//        }
//        $uniqueCacheId = $this->getGum(func_get_args());
//
//        $paginationcount = Cache::read('paginationcount-' . $this->alias . '-' . $uniqueCacheId, 'daily');
//        if (empty($paginationcount)) {
//            $paginationcount = $this->find('count', compact('conditions', 'contain', 'recursive'));
//            Cache::write('paginationcount-' . $this->alias . '-' . $uniqueCacheId, $paginationcount, 'daily');
//        }
//        return $paginationcount;
//    }
    
    public function getGum($args) {
        $conditions = reset($args);
        
        $argsCacheId = '';
        foreach ($args as $arg) {
            $argsCacheId .= serialize($arg);
        }
        $argsCacheId = md5($argsCacheId);
        
        $uniqueCacheId = implode(',',array_values($conditions));
        $uniqueCacheId = str_replace('%', '', $uniqueCacheId);
        return $uniqueCacheId.'_'.$argsCacheId;
    }
}
