<?php
// need a session for some datas
session_start();

// get the language for the interface
include($_SERVER['DOCUMENT_ROOT'].'/languages/lang.php');

// get the current PodServer global configuration
include($_SERVER['DOCUMENT_ROOT'].'/config/config-podserver.php');

// include the PodServer class
include($_SERVER['DOCUMENT_ROOT'].'/podserver.php');

$task_idx=0;
foreach ($podServer->podServerSystem->tasks as $task) {
	if ($task->status != 1) {
		echo $task_idx;
		exit;
	}
}
echo -1;

