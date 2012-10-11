<?PHP
	/******************************************************************************
	 Pepper

	 Developer		: Tyler Hall
	 Developer Site	: http://clickontyler.com
	 Pepper Name	: Growl

	 ******************************************************************************/

	if (!defined('MINT')) { header('Location:/'); }; // Prevent viewing this file 


	$installPepper = "TH_Growl";

	include("class.growl.php");

	class TH_Growl extends Pepper
	{ 
		var $version    = 110;
		var $info       = array
		(
			'pepperName'    => 'Growl',
			'pepperUrl'     => 'http://code.google.com/p/php-growl/',
			'pepperDesc'    => 'Sends notifications via Growl when certain events occur.',
			'developerName' => 'Tyler Hall',
			'developerUrl'  => 'http://clickontyler.com/'
		);
		var $prefs = array
		(
			'th_address'   => 0,
			'th_port'      => 9887,
			'th_password'  => '',
			'th_ureferral' => 0,
			'th_uvisitor'  => 0,
			'th_pages'     => '',
			'th_all'       => 0,
			'th_trim'      => 0
		);
		
		function isCompatible()
		{
			if((!function_exists("socket_create") || !function_exists("socket_sendto")) && !function_exists("fsockopen"))
				return array("isCompatible" => false, "explanation" => "<p>This server does not have <a href='http://us.php.net/fsockopen'>fsockopen</a> or the PHP <a href='http://us.php.net/manual/en/ref.sockets.php'>socket extension</a> installed.</p>");
			elseif(!function_exists("utf8_encode"))
					return array("isCompatible" => false, "explanation" => "<p>This server does not have <a href='http://www.php.net/manual/en/ref.xml.php'>utf8_encode</a> installed.</p>");
			elseif(!class_exists("Growl"))
				return array("isCompatible" => false, "explanation" => "<p>We were unable to load the Growl class.</p>");
			elseif($this->Mint->version < 120)
				return array("isCompatible"	=> false, "explanation"	=> "<p>This Pepper is only compatible with Mint 1.2 and higher.</p>");
			else
				return array('isCompatible'	=> true);
		}		
	
		function onDisplayPreferences()
		{

			$th_address  = $this->prefs['th_address'];
			$th_port     = $this->prefs['th_port'];
			$th_password = $this->prefs['th_password'];

			if($th_address == "") $th_address = $_SERVER['REMOTE_ADDR'];
			if($th_port == "") $th_port = "9887";

			$th_ureferral = ($this->prefs['th_ureferral'] == "1") ? 'checked="checked"' : '';
			$th_uvisitor  = ($this->prefs['th_uvisitor'] == "1") ? 'checked="checked"' : '';
		
			$th_pages = $this->prefs['th_pages'];

			$th_all   = ($this->prefs['th_all'] == "1") ? 'checked="checked"' : '';
			$th_trim  = ($this->prefs['th_trim'] == "1") ? 'checked="checked"' : '';

			$preferences['Growl Connection Settings'] = "
			<table>
				<tr>
					<td scope=\"row\">Notify Address:</td>
					<td>
						<span>
							<input type=\"text\" name=\"th_address\" value=\"$th_address\"/>
						</span>
					</td>
				</tr>
				<tr>
					<td scope=\"row\">&nbsp;</td>
					<td>Current IP is {$_SERVER['REMOTE_ADDR']}</td>
				</tr>
				<tr>
					<td scope=\"row\">Port:</td>
					<td>
						<span>
							<input type=\"text\" name=\"th_port\" value=\"$th_port\"/><br/>
						</span>
					</td>
				</tr>
				<tr>
					<td scope=\"row\">&nbsp;</td>
					<td>Default is 9887</td>
				</tr>
				<tr>
					<td scope=\"row\">Password:</td>
					<td>
						<span>
							<input type=\"password\" name=\"th_password\" value=\"$th_password\"/>
						</span>
					</td>
				</tr>
			</table>";

			$preferences['Notification Settings'] = "
				<table>
					<tr>
						<td scope=\"row\">Nofity me on every:</td>
						<td>
							<input type=\"checkbox\" id=\"th_ureferral\" value=\"1\" name=\"th_ureferral\" $th_ureferral/> <label for=\"th_ureferral\">Unique Referer</label>
						</td>
					</tr>
					<tr>
						<td scope=\"row\">&nbsp;</td>
						<td>
							<input type=\"checkbox\" id=\"th_uvisitor\" value=\"1\" name=\"th_uvisitor\" $th_uvisitor/> <label for=\"th_uvisitor\">Unique Visitor</label>
						</td>
					</tr>
					<tr>
						<td scope=\"row\">And also...</td>
						<td>&nbsp;</td>
					</tr>	
					<tr>
						<td scope=\"row\" colspan=\"2\">
							Whenever someone visits the following url(s):<br/>
							<span><textarea name=\"th_pages\">$th_pages</textarea></span>
						</td>
					</tr>
					<tr>
					<tr>
						<td scope=\"row\" colspan=\"2\"><label><input type=\"checkbox\" name=\"th_trim\" value=\"1\" $th_trim /> Trim <code>www</code> and <code>index.*</code> from urls (This will logically collapse two different urls that point to the same file. May break some urls.)</label></td>
					</tr>
					<td scope=\"row\" colspan=\"2\">
							<input type=\"checkbox\" id=\"th_all\" value=\"1\" name=\"th_all\" $th_all/> <label for=\"th_all\">Alert me for <strong>all</strong> page views. (Be careful!)</label>
						</td>
					</tr>
				</table>";

			return $preferences;
		}
	
		function onSavePreferences() {
			$this->prefs['th_address']   = $_POST['th_address'];
			$this->prefs['th_port']      = $_POST['th_port'];
			$this->prefs['th_password']  = $_POST['th_password'];
			$this->prefs['th_ureferral'] = ($_POST['th_ureferral'] == "1") ? 1 : 0;
			$this->prefs['th_uvisitor']  = ($_POST['th_uvisitor'] == "1") ? 1 : 0;
			$this->prefs['th_pages']     = $_POST['th_pages'];
			$this->prefs['th_all']       = ($_POST['th_all'] == "1") ? 1 : 0;
			$this->prefs['th_trim']       = ($_POST['th_trim'] == "1") ? 1 : 0;
		
			$g = new Growl("Growl Pepper");
			$g->setAddress($this->prefs['th_address'], $this->prefs['th_password']);
			$g->addNotification("Unique Referer");
			$g->addNotification("Unique Visitor");
			$g->addNotification("Page View");
			$g->register();
		}
		
	    function onRecord() {	
			$g = new Growl("Growl Pepper");
			$g->setAddress($this->prefs['th_address'], $this->prefs['th_password']);

			$IP = $_SERVER['REMOTE_ADDR'];
	 		$referer = $this->escapeSQL(preg_replace('/#.*$/', '', htmlentities($_GET['referer'])));
	 		$resource = $this->escapeSQL(preg_replace('/#.*$/', '', htmlentities($_GET['resource'])));

	 		if($this->prefs['th_trim'] == "1")
	 		{
	 			$referer = $this->trim($referer);
	 			$resource = $this->trim($resource);
	 		}

			$resource = trim($resource, "/");

			// Page View
			if( ($this->prefs['th_all'] == "1") || in_array($resource, explode("\n", $this->prefs['th_pages'])) )
			{
				$g->notify("Page View", "Page View: " . $resource, "IP Address: $IP\nReferer: $referer");
			}

			// New Visitor
			if(($this->prefs['th_uvisitor'] == "1") && $this->Mint->acceptsCookies && !isset($_COOKIE['MintUnique']))
			{
				$g->notify("Unique Visitor", "Unique Visitor", "URL: $resource\nIP Address: $IP\nReferer: $referer" . $info);
			}
		
			// New Referer
			if($this->prefs['th_ureferral'] == "1")
			{
				$result = $this->query("SELECT COUNT(*) FROM {$this->Mint->db['tblPrefix']}visit WHERE referer = '$referer'");
				if(mysql_result($result, 0, 0) == 0)
					$g->notify("Unique Referer", "New Referer: $referer", "URL: $resource\nIP Address: $IP" . $info);
			}
		}

		// Function taken from Shaun's Default Pepper
		function trim($url) 
		{
			return preg_replace("/^http(s)?:\/\/www\.([^.]+\.)/i", "http$1://$2", preg_replace("/\/index\.[^?]+/i", "/", $url));
		}
	}
