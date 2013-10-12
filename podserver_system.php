<?php

/**
 * This is the class that manage system user sessions and run system commands
 */


class PodServerSystem{

	public $sessions,$tasksQueue;

	public function __construct() {
		$this->sessions		= array();
		$this->tasksQueue	= array();
		// get the php server sessions datas for ssh sessions and tasks queue
		$this->getPhpSessions();
		$this->getPhpTasksQueue();
	} 


	/**
	 *  basic action login with system auth by ssh
	 */
	public function sshAuth($user='',$password='') {
		$mainSession = $this->getFirstLoggedSession();
		// check for previous session	
		if ($previousSession = $this->getSession($user)) {
			// if no password given, check in the previous session if one possaword exists
			if ($password=='') $password = $previousSession->userPassword;
			// update the session with a fresh ssh login
			$previousSession->sshLogin($password);
		}
		else {
			// obtain a new session with ssh login
			$this->sessions[] = new SshSession($user,$password);
		}
		// if it is the first logged session, set the "podServerSession" (user that logged in global podserver instance)
		if ($mainSession==null && $this->getFirstLoggedSession() != null) 
			{
			$_SESSION['current_sysuser'] = $this->getFirstLoggedSession()->userLogin;
			}
		$this->setPhpSessions();
	}

	/**
	 * This method render a login dialog box that ask password for the user
	 */
	public function getAuth($user=''){
		$authentication = '';
		// if no system user given, let the user choose one
		if ($user == ''){
			if (!isset($_SESSION['current_sysuser'])){
				$authentication.= '<h2>'.ENTER_USER_AND_PASSOWRD.'</h2><hr />';
			}
			else {
				// if the user has already tried to log in, try again for this user 
				$user = $_SESSION['current_sysuser'];
				$authentication.= '<h2>'.ERROR_SSH_LOGIN_TRY_AGAIN.'</h2><hr />';
			}
			$authentication.= '<label>'.LABEL_SYS_USER.' :</label><input type="text" name="current_sysuser" id="current_sysuser" value="'.$user.'" /><br/>';
		}
		// if sysuser given by "getAuth(sysuser)", let only the password entry
		else {
			$session = $this->getSession($user);
			if (!$session->userLogged && $session->userPassword !='') {
				$authentication.= '<h2>'.ERROR_SSH_LOGIN_TRY_AGAIN.'</h2><hr />';
			}
			else {
				$authentication.= '<h2>'.ENTER_PASSWORD_FOR_USER.' '.$user.'</h2><hr />';
			}
			$authentication.= '<input type="hidden" name="current_sysuser" id="current_sysuser" value="'.$user.'" />';
		}
		$authentication.= '<label>'.LABEL_PASSWORD.' :</label><input type="password" name="password" id="password" /><br/>
				<input type="submit" value="'.BUTTON_LOGIN.'" onclick="formSubmit(\'login\',\''.$user.'\');" /><br />';
		return $authentication;
	}
	
	/**
	 * This method get the ssh session for the givent user
	 */
	public function getSession($user = SYST_USER_FOR_DIASPORA){
		foreach ($this->sessions as $session){
			if ($session->userLogin == $user){
				return $session;
			}
		}
		return null;
	}

	/**
	 * This method get the first ssh session logged
	 */
	public function getFirstLoggedSession(){
		foreach ($this->sessions as $session){
			if ($session->userLogged) return $session;
		}
		return null;
	}


	/**
	 * This method get the ssh sessions datas from the _SESSION array
	 */
	public function getPhpSessions() {
		if (isset($_SESSION['sessions'])){
			unset($this->sessions);
			foreach ($_SESSION['sessions'] as $session) {
				$this->sessions[] = new SshSession($session['userLogin'],$session['userPassword'],$session['userLogged'],$session['userBanner']);
			}
		}
	}

	/**
	 * This method stores the ssh sessions datas in a _SESSION array
	 */
	public function setPhpSessions() {
		$idx = 0;
		unset($_SESSION['sessions']);
		foreach ($this->sessions as $session){
			$_SESSION['sessions'][$idx]['userLogin']	= $session->userLogin;
			$_SESSION['sessions'][$idx]['userPassword']	= $session->userPassword;
			$_SESSION['sessions'][$idx]['userLogged']	= $session->userLogged;
			$_SESSION['sessions'][$idx]['userBanner']	= $session->userBanner;
			$idx++;
		}
	} 

	/**
	 * This method add a task in the queue and refresh de _SESSION task queue array
	 */
	public function addTask($user,$command,$name=SYSTEM_TASK_IN_QUEUE,$actionId=null,$timeout=0) {
		$this->tasksQueue[] = new Task(null,$user,$command,$name,$actionId,$timeout);
		$this->setPhpTasksQueue();
	}
	
	/**
	 * This method render a command prompt to catch a command to run in ssh
	 */
	public function modifyTaskCommand($taskId=null,$command='') {
		foreach ($this->tasksQueue as $task){
			if ($task->id == $taskId){
				$task->command=$command;
				$this->setPhpTasksQueue();
				break;
			}
		}
	}
	
	/**
	 * This method get the tasks queue from the _SESSION array
	 */
	public function getPhpTasksQueue() {
		if (isset($_SESSION['tasksQueue'])){
			unset($this->tasksQueue);
			foreach ($_SESSION['tasksQueue'] as $task) {
				$this->tasksQueue[] = new Task($task['id'],
							  $task['user'],
							  $task['command'],
							  $task['name'],
							  $task['actionId'],
 							  $task['newExec'],
							  $task['timeout'],
						  	  $task['status'],
							  $task['message']);
			}
		}
	}

	/**
	 * This method get the tasks queue from the _SESSION array
	 */
	public function getHtmlTasksQueue() {
		$idx = 0;
		$html= '';
		foreach ($this->tasksQueue as $task)
			{
			$html.= '<input type="hidden" id="tasksQueue[\''.$idx.'\'][\'id\']" name="tasksQueue[\''.$idx.'\'][\'id\']" value="'.$task->id.'" />';
			$html.= '<input type="hidden" id="tasksQueue[\''.$idx.'\'][\'user\']" name="tasksQueue[\''.$idx.'\'][\'user\']" value="'.$task->user.'" />';
			$html.= '<input type="hidden" id="tasksQueue[\''.$idx.'\'][\'command\']" name="tasksQueue[\''.$idx.'\'][\'command\']" value="'.$task->command.'" />';
			$html.= '<input type="hidden" id="tasksQueue[\''.$idx.'\'][\'name\']" name="tasksQueue[\''.$idx.'\'][\'name\']" value="'.$task->name.'" />';
			$html.= '<input type="hidden" id="tasksQueue[\''.$idx.'\'][\'actionId\']" name="tasksQueue[\''.$idx.'\'][\'actionId\']" value="'.$task->actionId.'" />';
			$html.= '<input type="hidden" id="tasksQueue[\''.$idx.'\'][\'newExec\']" name="tasksQueue[\''.$idx.'\'][\'newExec\']" value="'.$task->newExec.'" />';
			$html.= '<input type="hidden" id="tasksQueue[\''.$idx.'\'][\'timeout\']" name="tasksQueue[\''.$idx.'\'][\'timeout\']" value="'.$task->timeout.'" />';
			$html.= '<input type="hidden" id="tasksQueue[\''.$idx.'\'][\'status\']" name="tasksQueue[\''.$idx.'\'][\'status\']" value="'.$task->status.'" />';
			$html.= '<input type="hidden" id="tasksQueue[\''.$idx.'\'][\'message\']" name="tasksQueue[\''.$idx.'\'][\'message\']" value="'.$task->message.'" />';
			$idx++;
			}
		return $html;
	}

	/**
	 * This method stores the tasks queue into a _SESSION array
	 */
	public function setPhpTasksQueue() {
		$idx = 0;
		unset($_SESSION['tasksQueue']);
		foreach ($this->tasksQueue as $task)
			{
			$_SESSION['tasksQueue'][$idx]['id']	 = $task->id;
			$_SESSION['tasksQueue'][$idx]['user']	 = $task->user;
			$_SESSION['tasksQueue'][$idx]['command'] = $task->command;
			$_SESSION['tasksQueue'][$idx]['name']	 = $task->name;
			$_SESSION['tasksQueue'][$idx]['actionId']= $task->actionId;
			$_SESSION['tasksQueue'][$idx]['timeout'] = $task->timeout;
			$_SESSION['tasksQueue'][$idx]['status']	 = $task->status;
			$_SESSION['tasksQueue'][$idx]['message'] = $task->message;
			$idx++;
			}
	}

	/**
	 * This method render a command prompt to catch a command to run in ssh
	 */
	public function getCommandPrompt($session=null,$task=null) {
		global $domain_name;
		$taskId='';
		$commandPrompt='';		
		if (!$session  && isset($_SESSION['current_sysuser'])) $session = $this->getSession($_SESSION['current_sysuser']);
		if ($session){
			if(!$task){
				$commandPrompt.= nl2br(str_replace(' ','&nbsp;',PODSERVER_BAN)).'<br />'.$session->userBanner . '<br /><br />';
			}
			else{
				$command = $task->command;
				$taskId  = $id->command;
			}
			$commandPrompt.= '<div id="input_command_line" class="command_line">
				<a onclick="formSubmit(\'task_from_prompt\')" title="'.ADD_THIS_COMMAND_TO_TASK_QUEUE.'" >
					<img src="http://'.$_SERVER['HTTP_HOST'].'/images/execute.png" />
				</a>
				<label>'. $session->userLogin .'@' . $domain_name .':</label>
				<span><input type="text" id="input_command_prompt" name="input_command_prompt" value="'.$command.'" placeholder="' . ENTER_A_SYSTEM_COMMAND . '"  /></span> 
				</div>
				<input type="hidden" id="input_command_task_id" name="input_command_task_id" value="'. $taskId .'" />
				<input type="hidden" id="input_command_user" name="input_command_user" value="'. $session->userLogin .'" />';				
		} //onkeypress="submitOnEnter(event,\'task_from_prompt\')"
		else{
			$commandPrompt = $this->getAuth();
		}
		return $commandPrompt;
	}
	
	/**
	 * Add a dialog box that will be displayed to the user.
	 * Types are : message, success, info,error,warning,"yes_no","yes_no_cancel"
	 *--------------------------------------------------------------------------
	 * For "yes_no" and "yes_no_cancel", you have to give a function name to run. The value will be returned to it
	 * If your function doesn't exists, you can give the function code to integrate with the generated html/js code
	 */
	public function dialogBox($message,$title='',$type='',$function_to_run='',$function_code=''){
		$dialog ='<script>'."\n".'new Messi(';
		$dialog.="'".addslashes($message)."'";
		$style  ='';
		$buttons='';
		$callback='';
		$buttonClose	= "{id: 0, label: '".CLOSE."', val: 'X'}";
		$buttonYes	= "{id: 0, label: '".YES."', val: 'Y', class: 'btn-success'}";
		$buttonNo	= "{id: 1, label: '".NO."', val: 'N', class: 'btn-danger'}";
		$buttonCancel	= "{id: 2, label: '".CANCEL."', val: 'C'}";
		$type		= (($title!=''&& $type=='') ? 'info' : $type );
		switch ($type){
			case 'success':
				$title   = (($title=='') ? SUCCESS : $title );
				$style   = "success";
				$buttons = "[".$buttonClose."]";
				break;
			case 'info':
				$title   = (($title=='') ? INFO : $title );
				$style   = "info";
				$buttons = "[".$buttonClose."]";
				break;
			case 'warning':
				$title   = (($title=='') ? WARNING : $title );
				$style   = "anim warning";
				$buttons = "[".$buttonClose."]";
				break;
			case 'error':
				$title   = (($title=='') ? ERROR : $title );
				$style   = "anim error";
				$buttons = "[".$buttonClose."]";
				break;
			case 'yes_no':
				$title   = (($title=='') ? QUESTION : $title );
				$buttons = "[".$buttonYes.",".$buttonNo."]";
				$callback= (($function_to_run=='') ? '' : ", callback: function(val){".$function_to_run . "(val);}");
				break;
			case 'yes_no_cancel':
				$title   = (($title=='') ? QUESTION : $title );
				$buttons = "[".$buttonYes.",".$buttonNo.",".$buttonCancel."]";
				$callback= (($function_to_run=='') ? '' : ", callback: function(val){".$function_to_run . "(val);}");
				break;
		}
		if ($title!='') {
			$dialog.=", {title: '".addslashes($title)."', modal: true";
			if ($style!='') {
				$dialog.=", titleClass: '".$style."'";
			}
			$dialog.=", buttons: ".$buttons;
			$dialog.= $callback."\n";
			$dialog.='}'."\n";
		}
		$dialog.=');</script>'."\n";
		$dialog ='<script>'."\n".$function_code."\n".'</script>'."\n".$dialog;
		return $dialog;
	}
}

/**
 * This store a shh session for a user and handle login method
 */
class SshSession {

	public $ssh,$userLogin,$userPassword,$userLogged,$userBanner;

	// create a session for the user (log with the password if given)
	public function __construct($user,$password='',$logged=false,$banner='') {
		$this->userLogin = $user;
		$this->userPassword = $password;
		$this->userLogged = $logged;
		$this->userBanner = $banner;
		$this->sshLogin();
	}

	// log the session to ssh
	public function sshLogin($password='') {
		// change the session password if given
		if ($password != '') $this->userPassword = $password;
		// load the ssh PHP implementation
		set_include_path($_SERVER['DOCUMENT_ROOT'].'/system/ssh');
		require_once($_SERVER['DOCUMENT_ROOT'].'/system/ssh/Net/SSH2.php');
		// initiate ssh console connexion with localhost 
		$this->ssh = new Net_SSH2('localhost');

			// login to ssh console with the diaspora user
			if (!$this->ssh->login($this->userLogin,$this->userPassword)) {
				$this->userLogged = false;
			}else{
				$this->userLogged = true;
				$this->userBanner = $this->ssh->getBannerMessage()."\n".$this->ssh->getServerIdentification();
			}
		
	}
	
	public function passwordChange($newPassword){
		// ensure to be logged in ssh before execution
		if (!$this->ssh->isConnected()) {
			$this->sshLogin();
		}
		$readen='';
		$this->ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX);
		$this->ssh->setTimeout(1);
		$this->ssh->write("passwd\n");
		$readen.=$this->ssh->read('/*/');
		$this->ssh->write($this->userPassword."\n");
		$readen.=$this->ssh->read('/*/')."\n";
		$this->ssh->write($newPassword."\n");
		$readen.=$this->ssh->read('/*/')."\n";
		$this->ssh->write($newPassword."\n");
		$readen.=$this->ssh->read('/*/')."\n";
		$this->userPassword=$newPassword;		
		return $readen; 
		
	}
}
/**
 * This store a task in the queue and handle exec method
 */
class Task {
	
	public function __construct($id=null,$user,$command,$name=SYSTEM_TASK_UNKNOWN,$actionId,$newExec=false,$timeout=0,$status=0,$message=''){
		// unique id for the task
		if($id==null) 	  $id=uniqid();
		$this->id 	= $id; 
		// user that run the task
		$this->user	= $user;
		// command to launch
		$this->command	= $command;
		// common name of the task
		$this->name	= $name;
		// id of the action who owns this task 
		if($actionId=='') $actionId=uniqid();
		$this->actionId	= $actionId;
		// this task will execute in the next queue loop TRUE (refresh user display in web browser)
		// or not FALSE (will execute this task right after the previous one)
		$this->newExec	= $newExec;
		// timeout for the task if long runing
		$this->timeout	= $timeout;
		// status code for the task
		$this->status	= $status;
		// returned message from the execution
		$this->message	= $message;
	}

	public function sshExec($session) {
		// ensure to be logged in ssh before execution
		if (!$session->ssh->isConnected()) {
			$session->sshLogin();
		}
		// last check before execution
		if (!$session->ssh->isConnected()) {
			$this->status	= -1;
			$this->message	= ERROR_SSHLOGIN_INTO_SSHEXEC;
		}
		else{
			$this->status  = 1;
			$session->ssh->setTimeout(1);
			$this->message = $session->ssh->exec($this->command);
			$this->message.= $session->ssh->read();
			//$this->message.= "!".$session->ssh->getLastError()."!";
			//$this->message.= $session->ssh->read();
		}
		if(!$file = @fopen($_SERVER['DOCUMENT_ROOT'].'/system/tasks.log', 'a')) {
			// if some error, tell it!
			$this->message.= "\n" . ERROR_CAN_NOT_OPEN_TASKS_LOG_FILE;
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
