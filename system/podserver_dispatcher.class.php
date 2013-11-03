<?php


/**
 * This is the class that dispatch PodServer actions and system commands 
 */

class PodServerDispatcher{
	public $action, $status, $error,$podServerConfiguration;

	public function __construct($podServerConfiguration) {
		$this->action = 'none';
		$this->status = 0;
		$this->podServerConfiguration = $podServerConfiguration;
		$this->podServerSystem = $this->podServerConfiguration->podServerSystem;
	} 

	public function execute($action,$target='/'){
		$this->action = $action;
		switch ($this->action){

			case 'login' : 
				// ask to the "PodServerSystem" to autenticate the user by ssh (it will store a session for it)
				$this->podServerSystem->sshAuth($_POST['current_sysuser'],$_POST['password']);				
				header("Location: ".$target);
				// don't run scripts after and reload directly
				return false;
				break;

			case 'logout' :
				// logout (kill the session)
				session_destroy();
				header("Location: ".$target);
				// don't run scripts after and reload directly
				return false;
				break;

			case 'record' : 
				// ask to the "PodServerConfiguration" class to record the current configuration 
				$this->status = $this->podServerConfiguration->configurationRecord();
				$dialog = $this->podServerSystem->dialogBox(CAN_APPLY_CONFIGURATION_TO_SYSTEM,CONFIGURATION_RECORDED,'success');
				$_SESSION['dialog_box'] = $dialog;
				// reload the PodServer interface
				header("Location: ".$target);
				// don't run scripts after and reload directly
				return false;
				break;

			case 'delete' : 
				// ask to the "PodServerConfiguration" to delete the file 
				$this->status = $this->podServerConfiguration->configurationDeleteFile($_POST['action_on_item']);
				header("Location: ".$target);
				// don't run scripts after and reload directly
				return false;
				break;

			case 'apply' :
				// clear the previous tasks queue
				if (isset($_SESSION['tasksQueue'])) unset($_SESSION['tasksQueue']);
				// ask to the "PodServerConfiguration" class to apply the current configuration to the system files
				$this->status = $this->podServerConfiguration->configurationApply();
				header("Location: ".$target);
				// don't run scripts after and reload directly
				return false;
				break;

			case 'clear_tasks_queue':
				if (isset($_SESSION['tasksQueue'])) unset($_SESSION['tasksQueue']);
				// continue runing script after dispatching
				return true;
				break;

			/**
			 * This action add or modify a task in the queue from the user prompt PodServer terminal
			 */
			case 'task_from_prompt':
				if (isset($_POST['input_command_task_id']) && trim($_POST['input_command_task_id'])!=''){
					$this->podServerSystem->modifyTaskCommand($_POST['input_command_task_id'],$_POST['input_command_prompt']);
				}
				else{
					$this->podServerSystem->addTask($_POST['input_command_user'],$_POST['input_command_prompt'],TASK_FROM_PROMPT);
				}
				// continue runing script after dispatching
				return true;
				break;

			/**
			 * This action change the password for the current user connected (principal session for PodServer)
			 */
			case 'passchange':
				$dialog = '';
				if (isset($_POST['f_pass_current']) && 
					trim($_POST['f_pass_current'])!=$this->podServerSystem->getSession($_SESSION['current_sysuser'])->userPassword){
						$dialog = $this->podServerSystem->dialogBox(DIALOG_PASS_CHANGE,ERROR_SSH_LOGIN_TRY_AGAIN,'error');
					}
				else{
					if (isset($_POST['f_pass_new']) && isset($_POST['f_pass_conf']) && $_POST['f_pass_new']!=$_POST['f_pass_conf']){
						$dialog = $this->podServerSystem->dialogBox(DIALOG_PASS_CHANGE,ERROR_PASSWORD_CONFIRMATION_NOT_THE_SAME,'warning');
					}
					else{
						if (strlen(trim($_POST['f_pass_new']))<8){
							$dialog = $this->podServerSystem->dialogBox(DIALOG_PASS_CHANGE,ERROR_PASSWORD_MUST_HAVE_8_LETTERS,'warning');
						}
						else{
							$consoleReturn = $this->podServerSystem->getSession($_SESSION['current_sysuser'])->passwordChange($_POST['f_pass_new']);
							$dialog = $this->podServerSystem->dialogBox(PASSWORD_CHANGED,DIALOG_PASS_CHANGE,'success');
							$this->podServerSystem->setPhpSessions();
						}
					}
				}
				echo $dialog;
				return true;
				break;

			// run the system script associated to the current action
			// this action will be dispatched to "system_status.php" terminal to be able to display HTML during network and apache shutdown
			default : 
				/* NOTE : you can add all action you want by adding a file "system/actions/exec_YOURACTION.php"
					For clean organisation and secure execution : 
					- put your PHP code to "system/actions"
					- put your system scripts to "system/scripts"
					- the action is ran by the button configured in the confi map file
						ex : 	array('sys_reboot','system','none','system','','reboot') 
							to render a button that arrive here with "$this->action = reboot" when clicked
				*/
				// clear the previous tasks queue
				if (isset($_SESSION['tasksQueue'])) unset($_SESSION['tasksQueue']);

				include ($_SERVER['DOCUMENT_ROOT'].'/system/actions/exec_'.$this->action.'.php');
				$this->podServerSystem->setPhpTasksQueue();
				//header("Location: ".$target);
				break;
		}
	}
}

?>
