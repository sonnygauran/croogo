<?php

//MINT_ROOT global variable is required
define('MINT', TRUE);
include_once(MINT_ROOT.'app/lib/mint.php');
include_once(MINT_ROOT.'app/lib/pepper.php');
include_once(MINT_ROOT.'config/db.php');
  
class BehaviorTracker{  
 
		//ajaxURL and sourceURL are optional  
		function record($eventName,$ajaxURL='',$sourceURL=''){
		 	global $_GET;
			global $Mint; 
			
			if(!$eventName) throw new Exception('Behavior Pepper (Mint) Error: eventName not specified');
			
			unset($_GET['referer']);  

			$Mint->loadPepper(); 
			
			$_GET['key'] 	  = $Mint->generateKey();
			
			// Included to work with Session Tracker
			$Mint->acceptsCookies=true;
			$Mint->cfg['debug']=true;
			$secretCrush 	  = $Mint->getPepperByClassName('SI_SecretCrush');
			if(!$secretCrush) throw new Exception('The Mint Pepper Secret Crush must be installed to run the Behavior Pepper');
			$secretCrushData  = $secretCrush->onRecord();
			
			$ajaxURL 		  = $Mint->escapeSQL(preg_replace('/#.*$/', '', htmlentities($ajaxURL)));
			$sourceURL 		  = $Mint->escapeSQL(preg_replace('/#.*$/', '', htmlentities($sourceURL)));
			$eventName 		  = $Mint->escapeSQL(preg_replace('/#.*$/', '', htmlentities($eventName)));
			
			$Mint->query('INSERT INTO '.$Mint->db['tblPrefix'].'behavior VALUES ('.
							time().',"'.
							$ajaxURL.'","'.
							$eventName.'","'.					
							$sourceURL.'",'.					
							$secretCrushData['ip_long'].',"'.
							$secretCrushData['visitor_name'].'",'.
							intval($secretCrushData['session_checksum']).')'
						 );	
						 
			//var_dump($Mint->errors);
		}//record
		
		
}//BehaviorTracker