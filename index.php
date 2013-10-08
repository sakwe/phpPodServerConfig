<?php


/*** 
 *
 *  index.php : PodServer administration entry
 *  ------------------------------------------
 *
 * It loads the configuration, execute the task if needed and render the interface
 *
 */


// need a session for some datas
session_start();

// get the system configuration (directories, files, command)
include($_SERVER['DOCUMENT_ROOT'].'/config.php');

// get the language for the interface
include($_SERVER['DOCUMENT_ROOT'].'/languages/lang.php');

// get the current PodServer global configuration
include($_SERVER['DOCUMENT_ROOT'].'/config/config-podserver.php');

// include the PodServer class
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
<link href="css/form.css" type="text/css" rel="stylesheet" />
<link href="css/tabs.css" rel="stylesheet" type="text/css" />
<script src="js/showPage.js" type="text/javascript"></script>
<script src="js/getPage.js" type="text/javascript"></script>
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
	 *	- $podServer->actionDispatch('record') to record the current setting in global configuration file
	 *	- $podServer->actionDispatch('reboot') to reboot the server
	 */

// call the dispatcher to execute the user action (recording configuration, apply configurations or other system commands)
if (isset($_POST['action'])) echo $podServer->actionDispatch($_POST['action']);

// render the PodServer configuration interface
echo $podServer->getInterface();

?>


</div>
</body>

