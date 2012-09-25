<?php
class FipsCode extends NimaAppModel {
	public $name = 'FipsCode';
        public $useDbConfig = 'nima';
	public $belongsTo = array(
		'Area' => array(
			'className' => 'Nima.Area',
			'foreignKey' => 'area_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public $hasMany = array(
		'NimaName' => array(
			'className' => 'Nima.NimaName',
			'foreignKey' => 'fips_code_id',
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
