<?php

define('MINT_ROOT', str_replace('pepper/tonytrupp/behavior/api.php', '', __FILE__));  
include_once('./BehaviorTracker.php');
$behaviorTracker = new BehaviorTracker();
$behaviorTracker->record($_GET['eventName'],$_GET['ajaxURL'],$_GET['sourceURL']); 