<?php
class Region extends NimaAppModel {
	public $name = 'Region';
        public $useDbConfig = 'nima';
	public $hasMany = array(
		'FipsCode' => array(
			'className' => 'Nima.FipsCode',
			'foreignKey' => 'region_id',
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
