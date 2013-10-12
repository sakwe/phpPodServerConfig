<?php
// need a session for some datas
session_start();

// get the language for the interface
include($_SERVER['DOCUMENT_ROOT'].'/languages/lang.php');

// get the current PodServer global configuration
include($_SERVER['DOCUMENT_ROOT'].'/config/config-podserver.php');

// include the PodServer class
include($_SERVER['DOCUMENT_ROOT'].'/podserver.php');


// instanciate the PodServer configurator (loads the configuration)
$podServer = new PodServer();

$task_idx='NONE';

foreach ($podServer->podServerSystem->tasksQueue as $task) {
	$task_idx='ALL_DONE';
	if ($task->status != 1) {
		echo $task->id;
		exit;
	}
}

echo $task_idx;

