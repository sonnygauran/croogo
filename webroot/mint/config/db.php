<?php
/******************************************************************************
 Mint
  
 Copyright 2004-2011 Shaun Inman. This code cannot be redistributed without
 permission from http://www.shauninman.com/
 
 More info at: http://www.haveamint.com/
 
 ******************************************************************************
 Configuration
 ******************************************************************************/
 if (!defined('MINT')) { header('Location:/'); } // Prevent viewing this file 

$Mint = new Mint (array
(
	'server'	=> '199.195.193.239',
	'username'	=> 'weatherph',
	'password'	=> '0d13d8b727a4aa726f9ff1d3e1f18671353c5a732c63127cd3617184dac515d568e974b09eec04b43368455f2aeeb773de6424a9873038780a79dfb97c2af12b',
	'database'	=> 'weatherph_mint',
	'tblPrefix'	=> ''
));