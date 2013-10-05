<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta name="author" content="Sakwe" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/monitor.css" type="text/css" rel="stylesheet" />
<script src="../js/showPage.js" type="text/javascript"></script>
<script src="../js/getPage.js" type="text/javascript"></script>
</head>

<body>
<div class="podserver_monitor" id="podserver_monitor">



</div>

<script>

function monitor_apache(){
	document.getElementById("podserver_monitor").innerHTML = "podserver_monitor<br />bientôt en action pour faire plein de choses !";
}

window.setInterval("monitor_apache()",3000);

</script>

</body>
</html>
