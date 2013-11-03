<?php

// include the PodServer tools
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

