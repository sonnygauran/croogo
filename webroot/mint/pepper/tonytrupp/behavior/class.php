<?php

/******************************************************************************
 Pepper
 
 Developer		: Tony Trupp
 Plug-in Name	: Behavior
 
 http://

 ******************************************************************************/

	if (!defined('MINT')) {
		header('Location:/');
	};

	$installPepper = 'tt_behavior'; 
 
	class tt_behavior extends Pepper { 
 
		var $version = 101;

		var $info = array(
			'pepperName'	=> 'Behavior',
			'pepperUrl'		=> 'http://vocalnation.net/posting/229/',
			'pepperDesc'	=> 'This Pepper tracks javascript user events (like popups or ajax requests) that occur between full http pageloads.',
			'developerName'	=> 'Tony Trupp',
			'developerUrl'	=> 'http://vocalnation.net/' 
		);

		//optional - for utility pepper
		var $panes = array( 'Behavior'	=> array( 'By Visit','Most Popular') );

		//optional - cached data
		/*
		var $data = array(
			
			'locations'	=> array(
				'total'	=> array(),
				'unique' => array()
			)
		);
		*/
		
		//pepper user configuration preferences saved to database
		var $prefs = array(
			//'threshold' => 1,
			//'sessionTimeout'	=> 7
			'daysToDisplay' => 3
		);
		
		var $manifest = array(			
			'behavior'	=> array(
				//'id'=> "INT NOT NULL", //UNIQUE KEY NOT NULL AUTO_INCREMENT",
				//'id'=> "int(10) unsigned NOT NULL auto_increment",
				'dt'=> "INT(10) NOT NULL",
				'ajaxURL'=>"VARCHAR(255) NOT NULL",
				'eventName'=>"VARCHAR(255) NOT NULL",
				'sourceURL'=>"VARCHAR(255) NOT NULL",
				'ip_long'=>"INT(10) NOT NULL",
				'visitor_name'=>"VARCHAR(255) NOT NULL",
				'session_checksum'=>"INT(10) NOT NULL"
			)			
		);
		
		//have mint purge old records from the database
		//var $moderate = array('behavior');
		
		function isCompatible() {

			if ($this->Mint->version < 200) {
				return array('isCompatible' => false, 'explanation' => '<p>This Pepper requires Mint 2.</p>');
			} else {
				return array('isCompatible' => true);
			}

		}

		function update() {
			if (!isset($this->prefs['sessionTimeout'])){	
				$this->prefs['sessionTimeout'] = 7;
			}
		}
		
		function onJavaScript(){
			$pepperPath='pepper/tonytrupp/behavior/';
			$js = $pepperPath.'script.js';
			if (file_exists($js)){
				include($js);
				echo "\n\r Mint.TT.behavior.API_URL='".$this->Mint->cfg['installDir'].'/'.$pepperPath."api.php' \n\r";
			}
			//var_dump($this->Mint->cfg);
		}
 
		function onDisplay($pane, $tab, $column = '', $sort = '') {
		
			//purge old data
			$prefs = $this->prefs;	
			$expiration = time() - (60 * 60 * 24 * intval($prefs['daysToDisplay']));
			$this->query("DELETE FROM `{$this->db['tblPrefix']}behavior` WHERE `dt` < $expiration");					

			switch ($tab) {
				case 'By Visit':
					$html .= $this->getHTML_ByVisit();
				break;
				case 'Most Popular':
					$html .= $this->getHTML_MostPopular();
				break;				
			}

			return $html;			

		}

		function onDisplayPreferences() {
			$prefs = $this->prefs;
			
			$preferences['General Settings'] = '<table><tr><th># of days to display</th>
												<td><span><input type="text" id="daysToDisplay" name="daysToDisplay" value="'.$prefs['daysToDisplay'].'" />
												</span></td></tr>
												<tr><td colspan="2">(Click "Done" below to save your changes)</td></tr></table>';
			return $preferences;
		}


		function onSavePreferences() {
			$prefs['daysToDisplay'] = (intval($_POST['daysToDisplay'])>0) ? intval($_POST['daysToDisplay']) : 3;				
			$this->prefs = $prefs;
			
		}		
		
		function getHTML_ByVisit() {
			
			$tableData['hasFolders'] = true;
			$tableData['table'] = array('id'=>'','class'=>'folder');
			$tableData['thead'] = array(
				array('value' => 'Who/Events', 'class' => 'focus'),
				array('value' => 'When', 'class' => 'sort')
			);
			$query = "SELECT `ip_long`, `visitor_name`, `session_checksum`, `dt`
								FROM `{$this->Mint->db['tblPrefix']}behavior` 
								WHERE `session_checksum` != 0
								GROUP BY `session_checksum` 
								ORDER BY `dt` DESC 
								LIMIT 0,300";
			
			if ($result = $this->query($query)) {
				while ($r = mysql_fetch_assoc($result)) {
					$userIdentity=($r['visitor_name'])?$r['visitor_name']:long2ip($r['ip_long']);
					
					$tableData['tbody'][] = array(
												$userIdentity, 
												$this->Mint->formatDateTimeRelative($r['dt']),
												'folderargs' => array(
																	'action'			=> 'getAjaxEventDetails',
																	'session_checksum'	=> $r['session_checksum']
																	)
												);
				}
			}
			$html = $this->Mint->generateTable($tableData);

			return $html;
		}
		
		
		function getHTML_MostPopular() {
			
			$tableData['thead'] = array(
				array('value' => 'Events', 'class' => 'focus'),
				array('value' => 'Hits', 'class' => 'sort')
			);
			
			$queryOld		= "SELECT `eventName`, `ajaxURL`, `dt` as hits, `sourceURL`
							FROM `{$this->Mint->db['tblPrefix']}behavior`
							ORDER BY `dt` DESC
							LIMIT 0,40";
			$query		= "SELECT `eventName`, COUNT(`eventName`) as hits 
							FROM `{$this->Mint->db['tblPrefix']}behavior` 
							GROUP BY eventName  
							ORDER BY hits DESC
							LIMIT 40";							
			
			if ($result = $this->query($query)) {
				while ($r = mysql_fetch_assoc($result)) {
					
					$tableData['tbody'][] = array(
												$r['eventName'], 
												$r['hits']
											);
				}
			}
			$html = $this->Mint->generateTable($tableData);
			return $html;
		}		
		
		function getAjaxEventDetails($session_checksum){
			$tableData['classes'] = array(
											'focus',
											'sort'
										);
			$query		= "SELECT `eventName`, `ajaxURL`, `dt`, `sourceURL`
							FROM `{$this->Mint->db['tblPrefix']}behavior`
							WHERE `session_checksum` = '{$session_checksum}'
							ORDER BY `dt` DESC";
			if ($result = $this->query($query)) {
			
				$first = true;
				$next_dt = time();
				
				while ($r = mysql_fetch_assoc($result)){
					if ($first){
						$first = false;
						$duration = ((time() - $r['dt']) > ($this->prefs['sessionTimeout'] * 60)) ? 'Timed out' : 'Viewing';
					}else $duration = $this->Mint->formatDateTimeSpan($r['dt'], $next_dt);
					
					$next_dt = $r['dt'];
					
					$EventDesc = $r['eventName'];
					if($r['ajaxURL']) {
						$ajaxURLAbbr=$this->Mint->abbr($r['ajaxURL'], 32);
						$EventDesc .= "<br><span>Request: <a href='{$r['ajaxURL']}'>{$ajaxURLAbbr}</a></span>";
					}					
					if($r['sourceURL']) {
						$sourceURLAbbr=$this->Mint->abbr($r['sourceURL'], 32);
						$EventDesc .= "<br><span>Source: <a href='{$r['sourceURL']}'>{$sourceURLAbbr}</a></span>";
					}
					
					$tableData['tbody'][] = array($EventDesc,$duration);
				}
			}
			
			$html .= $this->Mint->generateTableRows($tableData);
			return $html;			
		}
		
		
		function onCustom(){
			if(isset($_POST['action']) && $_POST['action']=='getAjaxEventDetails' && isset($_POST['session_checksum']) ){
				$session_checksum = $this->escapeSQL($_POST['session_checksum']);
				echo $this->getAjaxEventDetails($session_checksum);
			}
		}		

	}