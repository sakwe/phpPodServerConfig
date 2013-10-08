<?php
// need a session for some datas
session_start();

// get the language for the interface
include($_SERVER['DOCUMENT_ROOT'].'/languages/lang.php');

// get the current PodServer global configuration
include($_SERVER['DOCUMENT_ROOT'].'/config/config-podserver.php');

// include the PodServer class
include($_SERVER['DOCUMENT_ROOT'].'/podserver.php');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta name="author" content="Sakwe" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/monitor.css" type="text/css" rel="stylesheet" />
<script src="../js/monitor.php" type="text/javascript"></script>
<script src="../js/showPage.js" type="text/javascript"></script>
<script src="../js/getPage.js" type="text/javascript"></script>
</head>

<?
// instanciate the PodServer configurator (loads the configuration)
$podServer = new PodServer();


// call the dispatcher to execute the user action (in system-exec, essentially "login" action)
if (isset($_POST['action']) && trim($_POST['action'])!='') echo $podServer->actionDispatch($_POST['action'],'http://'.$_SERVER['HTTP_HOST'].'/system/system-monitor.php');

?>

<body>

<?
echo $podServer->getForm('<div class="podserver_monitor" id="podserver_monitor"></div>','http://'.$_SERVER['HTTP_HOST'].'/system/system-monitor.php');
echo '<div class="podserver_current_task" id="podserver_current_task"></div>';

?>


</body>
</html>
