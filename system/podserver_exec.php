<?php

// include the PodServer tools
include($_SERVER['DOCUMENT_ROOT'].'/podserver.php');


// instanciate the PodServer configurator (loads the configuration)
$podServer = new PodServer();

$queue = '';
$auth = '';
$queued= false;
$all_done=true;
if (!isset($_SESSION['new_loop'])) $_SESSION['new_loop']=true;


// check the tasks queue for "podServerSystem"
foreach ($podServer->podServerSystem->tasksQueue as $task) {
	$command_line = '<div class="command_line">'.$task->user.'@'.$domain_name.': ' . $task->command . '<br />' . nl2br($task->message) . '</div>';
	if ($task->status != 1) {
		$all_done=false;				
		if ($_SESSION['new_loop']){
			$_SESSION['new_loop']=false;
			$queue.='<hr /><div class="task"><h3>'.$task->name.'<span class="task_running" id="task_running">...</span></h3>'.$command_line . '</div>';
		}
		else{
			// get the session for the user
			$session = $podServer->podServerSystem->getSession($task->user);
			// ensure the section exists, if not create it by an "non password" authentication
			if (!$session){
				$session = $podServer->podServerSystem->sshAuth($task->user);
			}
			// ensure to be logged in ssh before execution
			if (!$session->ssh) {
				$session->sshLogin();
			}
			// if the user is logged in the system, we can run the system task with this session
			if ($session->ssh->isConnected()) {				
				if ($task->newExec) {
					$queued= true;
					$task->newExec = false;
					break;
				}
				else{
					$task->sshExec($session);
					if ($_SESSION['new_loop']){
					$queue.='<hr /><div class="task"><h3>'.$task->name.'<span class="task_running" id="task_running">...</span>
									</h3>'.$command_line . '</div>';
					}
				}
				$podServer->podServerSystem->setPhpTasksQueue();
				$_SESSION['new_loop']=false;
			}
			// if the user that need to run the task is not logged, open the dialog to autenticate in the queue
			else {
				$auth = '<div class="task"><h3>'.THIS_TASK_NEED_AUTENTICATION .' : ' .$task->name.'
					<span class="task_running" id="task_running">...</span></h3>'.$command_line.'</div><hr />';
				// render the autentication interface to ask password for the ksystem user that runs the task
				$auth.=  '<input type="hidden" name="dialog" id="dialog" value="ask_password" />'."\n";
				$auth.=  $podServer->podServerSystem->getAuth($task->user);
				$queued= true;
			}
		}
		if ($_SESSION['new_loop'] || (!$_SESSION['new_loop'] && $task->newExec)){
			$_SESSION['new_loop']=true;
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
		if ($all_done==true){
		$_SESSION['new_loop']=true;
		echo '<div style="clear:both;"></div><input type="button" id="button_clear_queue" onclick="javascript:formSubmit(\'clear_tasks_queue\');" value="'.CLEAR_TASKS_QUEUE_HISTORY.'" /><input type="hidden" name="dialog" id="dialog" value="clear_tasks_queue" />';
		}
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

