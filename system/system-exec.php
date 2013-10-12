<?php
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


// instanciate the PodServer configurator (loads the configuration)
$podServer = new PodServer();

// check the tasks queue for "podServerSystem"
$queue = '';
$auth = '';
$queued= false;

$idx=0;

foreach ($podServer->podServerSystem->tasksQueue as $task) {
	$command_line = '<div class="command_line">'.$task->user.'@'.$domain_name.': ' . $task->command . '<br />' . nl2br($task->message) . '</div>';
	if ($task->status != 1) {
		// try to autenticate the user with the current session datas
		$podServer->podServerSystem->sshAuth($task->user);
		// get the session for the user
		$session = $podServer->podServerSystem->getSession($task->user);
		// if the user is logged in the system, we can run the system task with this session
		if ($session->ssh->isConnected()) {
			$task->sshExec($session);
			$podServer->podServerSystem->setPhpTasksQueue();
			$queue.='<hr /><div class="task"><h3>'.$task->name.'<span class="task_running" id="task_running">...</span></h3>'.$command_line . '</div>';
			$queued= true;
			break;
		}
		// if the user that need to run the task is not logged, open the dialog to autenticate in the queue
		else {
			$auth = '<div class="task"><h3>'.THIS_TASK_NEED_AUTENTICATION .' : ' .$task->name.'<span class="task_running" id="task_running">...</span></h3>'.$command_line.'</div><hr />';
			// render the autentication interface to ask password for the ksystem user that runs the task
			$auth.=  '<input type="hidden" name="dialog" id="dialog" value="ask_password" />'."\n";
			$auth.=  $podServer->podServerSystem->getAuth($task->user);			
			$queued= true;
			break;
		}
	}
	else{
			$queue.='<hr /><div class="task"><h3>'.$task->name.'<span class="task_done"> ok! </span></h3>'.$command_line . '</div>';
	}
}

// include the tasks queue html datas
echo $queue;

if (!$queued){
	// if no task in queue anymore, clean the queue in this session
	if (isset($_SESSION['tasksQueue'])) {
		echo '<input type="button" id="button_clear_queue" onclick="javascript:formSubmit(\'clear_tasks_queue\');" value="'.CLEAR_TASKS_QUEUE_HISTORY.'" /><input type="hidden" name="dialog" id="dialog" value="clear_tasks_queue" />';
	}
	else{
		echo $podServer->podServerSystem->getCommandPrompt();
	}
}
else	{
	if ($auth!=''){
		echo $auth;
	}
}


?>

