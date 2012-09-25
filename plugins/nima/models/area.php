<?php
class Area extends NimaAppModel {
	public $name = 'Area';
        public $useDbConfig = 'nima';
	public $hasMany = array(
		'FipsCode' => array(
			'className' => 'Nima.FipsCode',
			'foreignKey' => 'area_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
