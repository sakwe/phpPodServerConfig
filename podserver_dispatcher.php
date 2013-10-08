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
				// ask to the "PodServerSystem" to autenticate the user by ssh
				$this->podServerSystem->sshAuth($_POST['action_on_item'],$_POST['password']);				
				header("Location: ".$target);
				break;
		
			case 'logout' :
				// logout (kill the session)
				session_destroy();
				header("Location: ".$target);
				break;

			case 'record' : 
				// ask to the "PodServerConfiguration" class to record the current configuration 
				$this->status = $this->podServerConfiguration->configurationRecord();
				// reload the PodServer interface
				header("Location: ".$target);
				break;

			case 'delete' : 
				// ask to the "PodServerConfiguration" to delete the file 
				$this->status = $this->podServerConfiguration->configurationDeleteFile($_POST['action_on_item']);
				header("Location: ".$target);
				break;

			case 'apply' :
				// ask to the "PodServerConfiguration" class to apply the current configuration to the system files
				$this->status = $this->podServerConfiguration->configurationApply();
				header("Location: ".$target);
				break;

			// run the system script associated to the current action
			// this action will be dispatched to "system_status.php" monitor to be able to display HTML during network and apache shutdown
			default : 
				/* NOTE : you can add all action you want by adding a file "system/actions/exec_YOURACTION.php"
					For clean organisation and secure execution : 
					- put your PHP code to "system/actions"
					- put your system scripts to "system/scripts"
					- the action is ran by the button configured in the confi map file
						ex : 	array('sys_reboot','system','none','system','','reboot') 
							to render a button that arrive here with "$this->action = reboot" when clicked
				*/
				include ($_SERVER['DOCUMENT_ROOT'].'/system/actions/exec_'.$this->action.'.php');
				header("Location: ".$target);
				break;
		}
		return $this->status;
	}
}

?>
