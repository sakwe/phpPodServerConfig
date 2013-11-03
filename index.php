<?php


/*** 
 *
 *  index.php : PodServer administration entry
 *  ------------------------------------------
 *
 * It loads the configuration, execute the task if needed and render the interface
 *
 */

// include the PodServer tools
include($_SERVER['DOCUMENT_ROOT'].'/podserver.php');


// send the header
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title><?= BIG_TITLE_POD_CONFIGURATION ?></title>
<meta name="author" content="Sakwe" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href='images/favicon.png' rel='shortcut icon'>
<link href="css/form.css" rel="stylesheet" type="text/css" />
<link href="css/tabs.css" rel="stylesheet" />
<link href="css/messi.css" rel="stylesheet" type="text/css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="js/showPage.js" type="text/javascript"></script>
<script src="js/getPage.js" type="text/javascript"></script>
<script src="js/messi.min.js"></script>
</head>

<body>
<div class="podserver_container">

<?php

// instanciate the PodServer configurator (loads the configuration)
$podServer = new PodServer();

	/** 
	 * From here, you can access to the global configuration items. Exemples : 
	 *	- $podServer->podServerConfiguration[item_idx]->name to get the name of an item
	 *	- $podServer->podServerConfiguration[item_idx]->value to get its value
	 *	- $podServer->podServerSystem->getSession($_SESSION['current_sysuser'])->userLogin
	 *	- $podServer->actionDispatch('record') to record the current setting in global configuration file
	 *	- $podServer->actionDispatch('reboot') to reboot the server
	 */

// render the PodServer configuration interface with action if given
echo $podServer->getInterface((isset($_POST['action']))?$_POST['action']:'');

// attach the script that manage monitor show/hide
?>


</div>
</body>
</html>
