<?php

// include the PodServer tools
include($_SERVER['DOCUMENT_ROOT'].'/podserver.php');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta name="author" content="Sakwe" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/podserver_terminal.css" type="text/css" rel="stylesheet" />
<script src="../js/showPage.js" type="text/javascript"></script>
<script src="../js/getPage.js" type="text/javascript"></script>
<!-- Google CDN jQuery with fallback to local -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<!-- custom scrollbars plugin -->
<script src="../js/jquery.mCustomScrollbar.concat.min.js"></script>
<link href="../css/jquery.mCustomScrollbar.css" rel="stylesheet" />

</head>
<body>

<?
// instanciate the PodServer configurator (loads the configuration)
$podServer = new PodServer();

// call the dispatcher to execute the user action (in system-exec, essentially "login" action)
if (isset($_POST['action']) && trim($_POST['action'])!='') $podServer->actionDispatch($_POST['action'],'http://'.$_SERVER['HTTP_HOST'].'/system/podserver_terminal.php');

// render the form that will contain the dynamic DIV managed by "js/podserver_terminal.php" and "system/system-exec.php"
echo $podServer->getForm('<div id="scroller">
				<div class="podserver_terminal content" id="podserver_terminal">
					<div class="terminal_loader"><img src="http://'.$_SERVER['HTTP_HOST'].'/images/loader.gif" /></div>
				</div>
			</div>','http://'.$_SERVER['HTTP_HOST'].'/system/podserver_terminal.php');

// invisible dynamic DIV that is used to get current task status
echo '<div class="podserver_current_task" id="podserver_current_task"></div>';

?>
<script src="../js/podserver_terminal.php" type="text/javascript"></script>
</body>
</html>
