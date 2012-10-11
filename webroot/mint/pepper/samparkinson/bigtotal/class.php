<?php
/******************************************************************************
 Pepper
 
 Developer		: Sam Parkinson
 Plug-in Name	        : Big Total
 
 http://www.xseria.com/

 ******************************************************************************/
if (!defined('MINT')) { header('Location:/'); }; // Prevent viewing this file
$installPepper = "SP_BigTotal";

class SP_BigTotal extends Pepper
{
	var $version	= 211;
	var $info	= array
	(
		'pepperName'	=> 'Big Total',
		'pepperUrl'	=> 'http://www.xseria.com/page/pepper/',
		'pepperDesc'	=> 'Big Total will give you a nice big number with either your sites total visits or its total unique visits.',
		'developerName'	=> 'Sam Parkinson',
		'developerUrl'	=> 'http://www.xseria.com/'
	);
	var $panes = array
	(
		'Big Total' => array
		(
			'Total',
			'Unique'
		)
	);
	
	/**************************************************************************
	 isCompatible()
	 **************************************************************************/
	function isCompatible()
	{
		if ($this->Mint->version >= 216)
		{
			return array
			(
				'isCompatible'	=> true
			);
		}
		else
		{
			return array
			(
				'isCompatible'	=> false,
				'explanation'	=> '<p>This Pepper has only been tested on Mint 2.15 - 2.16. Edit the code if you know what your doing and wish to run it on a lower version.</p>'
		);
		}
	}
	
	/**************************************************************************
	 onDisplay()
	 **************************************************************************/
	function onDisplay($pane, $tab, $column = '', $sort = '')
	{
		$html = '';
		switch($pane) 
		{
			case 'Big Total':
				switch($tab)
				{
					case 'Total':
						$html .= $this->getHTML_visitTotal();
						break;

					case 'Unique':
						$html .= $this->getHTML_uniqueTotal();
						break;
				}
			break;
		}
		return $html;
	}

	/**************************************************************************
	 getHTML_visitTotal()
	
	 Displays the total visits.
	 **************************************************************************/
	function getHTML_visitTotal()
	{
		$visits = $this->Mint->pepper[0]->data['visits'];

		$totalformat = number_format($visits[0][0]['total']);

		return '<div style="text-align: center; padding: 2px 0;"><span style="font-size: 4em; line-height: 1.5em;">' . $totalformat . '</span><br />Total visits since ' . $this->Mint->formatDateRelative($this->Mint->cfg['installDate'], 'month', 1) . '</div>';
	}

	/**************************************************************************
	 getHTML_uniqueTotal()
	
	 Displays the total unique visits.
	 **************************************************************************/
	function getHTML_uniqueTotal()
	{
		$visits = $this->Mint->pepper[0]->data['visits'];

		$uniqueformat = number_format($visits[0][0]['unique']);

		return '<div style="text-align: center; padding: 2px 0;"><span style="font-size: 4em; line-height: 1.5em;">' . $uniqueformat . '</span><br />Total unique visits since ' . $this->Mint->formatDateRelative($this->Mint->cfg['installDate'], 'month', 1) . '</div>';
	}
	
}
