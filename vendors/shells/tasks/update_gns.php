<?php

class UpdateGnsTask extends Shell{

	public $uses = array('Nima.NimaName', 'Nima.FipsCode', 'Nima.Province');

	function execute(){
		// set memory_limit
		ini_set('memory_limit', '4096M');

		$NimaName = new NimaName();
		$FipsCode = new FipsCode();


		$nima_names = $NimaName->find('all', array(
			'fields' => array('id', 'adm1', 'cc1')
		));
		

		foreach($nima_names as $nima_name){
			$results = $FipsCode->find('all', array( 
				'fields' => array('id'),
				'conditions' => array(
					'cc1' => $nima_name['Name']['cc1'],
					'adm1' => $nima_name['Name']['adm1'],
				)
			));
			
			if(!array_key_exists(0, $results)) continue;
			
			$fips_code_id = $results[0]['FipsCode']['id'];
			$id = $nima_name['Name']['id'];

			$query = "update names set fips_code_id='$fips_code_id' where id='$id'";
			$NimaName->query($query);
		}


	}
}
