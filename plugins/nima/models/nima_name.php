<?php

class NimaName extends NimaAppModel {

    public $name = 'Name';
    public $useDbConfig = 'nima';

    function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
        $keyword = $conditions['keyword'];
        $sql = "select `NimaName`.`id`, `NimaName`.`long`, `NimaName`.`lat`, `NimaName`.`full_name_ro`, `FipsCode`.name, `Region`.`name`, `Region`.`code`, if(`FipsCode`.`type` = 2, 'City', 'Province') as `charter_type` from `names` as `NimaName`, `fips_codes` as `FipsCode`, `provinces` as `Province`, `regions` as `Region` where ( `FipsCode`.adm1 = `NimaName`.adm1  and `FipsCode`.cc1  = `NimaName`.cc1 ) and ( `NimaName`.`nt` = 'N' ) and ( `NimaName`.`dsg` = 'ppl' or `NimaName`.`dsg` = 'adm1'  or `NimaName`.`dsg` = 'adm2' ) and ( `Province`.`name` = `FipsCode`.`name`  and `Province`.`region_id` = `Region`.id ) and ( `NimaName`.`full_name_ro` = '$keyword' or `NimaName`.`full_name_ro` like '$keyword %' or `NimaName`.`full_name_ro` like '% $keyword' ) order by `charter_type`,`NimaName`.`id` desc, FIELD(`NimaName`.`dsg`, 'adm1', 'ppl') desc";
        $this->recursive = $recursive;
        $results = $this->query($sql);
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
        return $results;
    }
//
    function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
        $keyword = $conditions['keyword'];
        $sql = "select `NimaName`.`id`, `NimaName`.`long`, `NimaName`.`lat`, `NimaName`.`full_name_ro`, `FipsCode`.name, `Region`.`name`, `Region`.`code`, if(`FipsCode`.`type` = 2, 'City', 'Province') as `charter_type` from `names` as `NimaName`, `fips_codes` as `FipsCode`, `provinces` as `Province`, `regions` as `Region` where ( `FipsCode`.adm1 = `NimaName`.adm1  and `FipsCode`.cc1  = `NimaName`.cc1 ) and ( `NimaName`.`nt` = 'N' ) and ( `NimaName`.`dsg` = 'ppl' or `NimaName`.`dsg` = 'adm1'  or `NimaName`.`dsg` = 'adm2' ) and ( `Province`.`name` = `FipsCode`.`name`  and `Province`.`region_id` = `Region`.id ) and ( `NimaName`.`full_name_ro` = '$keyword' or `NimaName`.`full_name_ro` like '$keyword %' or `NimaName`.`full_name_ro` like '% $keyword' ) order by `charter_type`,`NimaName`.`id` desc, FIELD(`NimaName`.`dsg`, 'adm1', 'ppl') desc";
        $this->recursive = $recursive;
        $results = $this->query($sql);
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
        return count($results);
    }
    
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
