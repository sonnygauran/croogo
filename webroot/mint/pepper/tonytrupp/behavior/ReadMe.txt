Description:
=============================================================================

The Behavior Pepper tracks any javascript user event (like popups or ajax requests) 
that occur between full http pageloads.  

By default, mint will only track hits where there is complete refresh or loading 
of a new webpage.  For example, browser-side javascript events that use ajax to 
reload portion of the page, or that display previously hidden content, will go 
unnoticed.  The Behavior Pepper fills this gap by allowing you to specify custom 
events you'd like to monitor.  

(This pepper will not effect your total number of page views within the visits 
panel).


REQUIREMENTS:
=============================================================================

The Behavior Pepper Requires php5+ and the Secret Crush Pepper



INSTALLATION:
=============================================================================

1. Upload the /behavior/ directory and its contents to 
/mint/pepper/tonytrupp/. If the directory /tonytrupp/ doesn't exist, create it.

2. Login to your Mint installation and in the Preferences click "Install" under
Pepper.

3. Click the Behavior Pepper "Install" button. Click "Okay" and then continue
with the installation steps outlined below.



CONFIGURATION:
=============================================================================

To make this pepper track dynamic, client-side behavior, you have to add "hooks" 
to each event you'd like to track in your application.  

Two methods are provided for this:

1) Browser-Side Code: This method uses a seperate ajax request, and is triggered 
by a javascript function call within the client's browser. Requires knowledge of 
javascript.

2) Server-Side Code: For logging an event from within your application's php code.  
Requires knowledge of php.



Browser-Side Event Logging Method with Javascript
-----------------------------------------------------------------------------

To log an event from within your javascript code, you can call the javascript 
function:
Mint.TT.behavior.record("my event name","/ajaxURL (optional)")

For Example:
<a onclick="Mint.TT.behavior.record('someFunction','ajax/someFunction.php')">
Click Me</a>


The ajaxURL can also be omitted:
<a onclick="Mint.TT.behavior.record('someFunction')">Click Me</a>

If you would like to use your own javascript AJAX library, you can bypass the 
Mint.TT.behavior.record(eventName,ajaxURL) method and use the 
Mint.TT.behavior.getURL(eventName,ajaxURL) method instead



Server-Side Ajax Event Logging Method with PHP
-----------------------------------------------------------------------------

To log an ajax event from the server via php code, you can access the Behavior class.


//You must first define the MINT_ROOT global variable:
define('MINT_ROOT', str_replace('pepper/tonytrupp/ajaxTracker/api.php', '', __FILE__)); 

//Then include_the BehaviorTracker file, and initiate it
include_once(MINT_ROOT.'pepper/tonytrupp/behavior/BehaviorTracker.php');
$behaviorTracker = new BehaviorTracker();

//And lastly call the method to record the event.
//$eventName: 			string of the action being performed, such as "Show Popup Window"
//$requestedURL (optional): 	string of the URL being requested, "showPopupWindow.php?id=101"
//$sourceURL (optional): 	string of the URL being requested, such as "index.php" or "Home Page"
$behaviorTracker->record($eventName,$requestedURL,$sourceURL);


