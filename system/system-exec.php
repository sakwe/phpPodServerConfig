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

// check the tasks queue for "podServerSystem"
$queue = '';
$auth  = '';
$queued= false;
foreach ($podServer->podServerSystem->tasks as $task) {
	if ($task->status != 1) {
		// try to autenticate the user with the current session datas
		$podServer->podServerSystem->sshAuth($task->user);
		// get the session for the user
		$session = $podServer->podServerSystem->sessions[$podServer->podServerSystem->getSessionIdx($task->user)];
		// if the user is logged in the system, we can run the system task with this session
		if ($session->ssh->isConnected()) {
			$task->sshExec($session);
			$podServer->podServerSystem->setPhpTasks();
			$queue = '<div class="task_queued"><b>' . $task->name . "</b><span color='blue'> ...</span><br />" .$task->command . "<br /><hr /></div>" .  $queue;
			$queued= true;
			break;
		}
		// if the user that need to run the task is not logged, open the dialog to autenticate in the queue
		else {
			$auth = THIS_TASK_NEED_AUTENTICATION . "<br />" . $task->name . "<br />" .$task->command . "<br /><hr />";
			// render the autentication interface to ask password for the ksystem user that runs the task
			$auth.=  '<input type="hidden" name="dialog" id="dialog" value="ask_password" />'."\n";
			$auth.=  $podServer->podServerSystem->getAuth($task->user);			
			$queued= true;
			break;
		}
	}
	else{
		// lists tasks done
		$queue = '<div class="task_done"><b>' . $task->name . "</b><span color='green'> -> ok</span><br />" .$task->command . "<br /><hr /></div>" .$queue;
	}
}
if (!$queued){
	// if no task in queue anymore, clean the queue in this session
	if (isset($_SESSION['tasks'])) unset($_SESSION['tasks']);
}
// display auth or queue
if ($auth!=''){
	echo $auth;
}
else{
	echo $queue;
}

?>

