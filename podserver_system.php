<?php

/**
 * This is the class that manage system user sessions and run system commands
 */


class PodServerSystem{

	public $sessions,$tasks;

	public function __construct() {
		$this->sessions = array();
		$this->tasks = array();
		// get the php sessions datas for ssh sessions and tasks queue
		$this->getPhpSessions();
		$this->getPhpTasks();
	} 


	/**
	 *  basic action login with system auth by ssh
	 */
	public function sshAuth($user = SYST_USER_FOR_DIASPORA,$password = '') {

		$previousSession = $this->getSessionIdx($user);
		if ($previousSession > 0) {
			// if no password given, check in the previous session if one possaword exists
			if ($password=='') $password = $this->sessions[$previousSession]->userPassword;
			// update the session with a fresh ssh login
			$this->sessions[$previousSession] = new SshSession($user,$password);
		}
		else {
			// obtain a new session with ssh login
			$this->sessions[] = new SshSession($user,$password);
		}
		$this->setPhpSessions();
	}


	public function getAuth($user = SYST_USER_FOR_DIASPORA){
		$authentication = '';
		if (!$this->sessions[$this->getSessionIdx(SYST_USER_FOR_DIASPORA)]->userLogged) {
			$authentication.= '<h2>'.ERROR_SSH_LOGIN_TRY_AGAIN.'</h2><hr />';
		}
		else {
			$authentication.= '<h2>'.ENTER_PASSWORD_FOR_USER.' '.$user.'</h2><hr />';
		}
		$authentication.= '<label>'.LABEL_PASSWORD.' :</label><input type="password" name="password" id="password" /><br/>
				<input type="submit" value="'.BUTTON_LOGIN.'" onclick="formSubmit(\'login\',\''.$user.'\');" /><br />';
		return $authentication;
	}
	
	public function getSessionIdx($user = SYST_USER_FOR_DIASPORA){
		$idx = 0;		
		foreach ($this->sessions as $session){
			if ($session->userLogin == $user){
				return $idx;
			}
			$idx++;
		}
		return -1;
	}

	public function getPhpSessions() {
		if (isset($_SESSION['sessions'])){
			foreach ($_SESSION['sessions'] as $session) {
				$this->sessions[] = new SshSession($session['userLogin'],$session['userPassword']);
			}
		}
	}

	public function setPhpSessions() {
		$idx = 0;
		unset($_SESSION['sessions']);
		foreach ($this->sessions as $session)
			{
			$_SESSION['sessions'][$idx]['userLogin']	= $session->userLogin;
			$_SESSION['sessions'][$idx]['userPassword']	= $session->userPassword;
			$_SESSION['sessions'][$idx]['userLogged']	= $session->userLogged;
			$idx++;
			}
	} 

	public function addTask($user,$command,$name=SYSTEM_TASK_IN_QUEUE) {
		$this->tasks[] = new Task($user,$command,$name);
		$this->setPhpTasks();
	}
	

	public function getPhpTasks() {
		if (isset($_SESSION['tasks'])){
			foreach ($_SESSION['tasks'] as $task) {
				$this->tasks[] = new Task($task['user'],$task['command'],$task['name'],$task['status'],$task['message']);
			}
		}
	}

	public function setPhpTasks() {
		$idx = 0;
		unset($_SESSION['tasks']);
		foreach ($this->tasks as $task)
			{
			$_SESSION['tasks'][$idx]['user']	= $task->user;
			$_SESSION['tasks'][$idx]['command']	= $task->command;
			$_SESSION['tasks'][$idx]['name']	= $task->name;
			$_SESSION['tasks'][$idx]['status']	= $task->status;
			$_SESSION['tasks'][$idx]['message']	= $task->message;
			$idx++;
			}
	}

	/**
	 * This method explode all commands in the action file "system/actions/" to create tasks
	 */
	public function sshExecAction($action) {
		$user = SYST_USER_FOR_DIASPORA;
		$this->addTask($user,$command);
	}

}


class SshSession {

	public $ssh,$userLogin,$userPassword,$userLogged;

	public function __construct($user,$password) {
		$this->sshLogin($user,$password);
	}

	public function sshLogin($user,$password) {
		$this->userLogin = $user;
		$this->userPassword = $password;
		// load the ssh PHP implementation
		set_include_path($_SERVER['DOCUMENT_ROOT'].'/system/ssh');
		require_once($_SERVER['DOCUMENT_ROOT'].'/system/ssh/Net/SSH2.php');
		// initiate ssh console connexion with localhost 
		$this->ssh = new Net_SSH2('localhost');
		// login to ssh console with the diaspora user
		if (!$this->ssh->login($user,$password)) {
			$this->userLogged = false;			
		}else{				
			$this->userLogged = true;

		}
	}

	
}

class Task {
	
	public function __construct($user,$command,$name=SYSTEM_TASK,$status=0,$message=''){
		$this->user	= $user;
		$this->command	= $command;
		$this->status	= $status;
		$this->message	= $message;
		$this->name	= $name;
	}

	public function sshExec($session) {
		// check if the ssh session is opened before execution
		if (!$session->ssh->isConnected()) {
			$session->sshLogin($session->userLogin,$session->userPassword);
		}
		// last check before execution
		if (!$session->ssh->isConnected()) {			
			$this->status	= 0;
			$this->message	= ERROR_SSHLOGIN_INTO_SSHEXEC;
		}
		else{
			$this->status	= 1;
			$this->message	= $session->ssh->exec($this->command);			
		}
		if(!$file = @fopen($_SERVER['DOCUMENT_ROOT'].'/system/tasks.log', 'a')) {
			// if some error, tell it!
			$error  = ERROR_CAN_NOT_OPEN_TASKS_LOG_FILE;
			return $error;
		}
		// config file header
		fwrite($file, $this->user . ' run : ' . $this->name."\n");
		fwrite($file, $this->status . ' for : ' . $this->command."\n");
		fwrite($file, $this->message ."\n");
		fwrite($file, '-------------------------------------------------------------'."\n");
		fclose($file);
	}
}


?>
