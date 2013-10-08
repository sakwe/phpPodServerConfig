<?php


?>


var task_idx = -1;

function system_exec(){	
	if (!document.getElementById("dialog")) {
		showPage("http://<?= $_SERVER['HTTP_HOST'] ?>/system/system-current-task.php","podserver_current_task");
		var current_task_idx = parseInt(document.getElementById("podserver_current_task").innerHTML);		
		if (current_task_idx != task_idx && current_task_idx != -1){
			task_idx = current_task_idx;
			showPage("http://<?= $_SERVER['HTTP_HOST'] ?>/system/system-exec.php","podserver_monitor");
		}
	}
	window.parent.document.getElementById("iframe_monitor").style.height=getDocHeight()+"px";
}

function getDocHeight() {
    var D = document;
    return Math.max(
        D.body.scrollHeight, D.documentElement.scrollHeight,
        D.body.offsetHeight, D.documentElement.offsetHeight,
        D.body.clientHeight, D.documentElement.clientHeight
    );
}

window.setInterval("system_exec()",2000);
