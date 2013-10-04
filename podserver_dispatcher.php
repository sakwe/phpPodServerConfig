<?php

class Actiondispatcher{
	public $action, $status, $error,$podServerConfiguration;

	public function __construct($podServerConfiguration) {
		$this->action = 'none';
		$this->status = 0; // 0 = unknown 1 = done 		
		$this->error  = '';
		$this->podServerConfiguration = $podServerConfiguration;
	} 
	
	public function execute($action){
		$this->action = $action;
		switch ($this->action){

			// basic action login with system auth by ssh
			case 'login' : 
				// load the ssh PHP implementation
				set_include_path(get_include_path() . PATH_SEPARATOR . 'system/ssh');
				include('Net/SSH2.php');
				// initiate ssh console connexion with localhost 
				$ssh = new Net_SSH2('localhost');
				// login to ssh console with the diaspora user
				if (!$ssh->login(SYST_USER_FOR_DIASPORA, $_POST['password'])) {
					$_SESSION['logged'] = 'error';				
				}else{				
					$_SESSION['logged'] = 'true';
					$_SESSION['password'] = $_POST['password'];
					echo $ssh->exec('pwd');
					echo $ssh->exec('ls -la');
				}
				header("Location: /");
				break;
			
			// logout (kill the session)
			case 'logout' :
				session_destroy();
				header("Location: /");
				break;

			// basic record action for the global configuration
			case 'record' : 
				// open the global configuration file that contains the variables and their values
				if(!$file = @fopen($_SERVER['DOCUMENT_ROOT'].'/config/config-podserver.php', 'w')) {
					// if some error, tell it!
					$this->error  = ERROR_CAN_NOT_OPEN_CONFIG_PODSERVER_PHP;
					exit;
				}
				// config file header
				fwrite($file, "<?\n// ".FILE_GENERATED_BY." PodServer Actiondispatcher\n");
				// write configuration variables into the "config/config-podserver.php" file
				foreach($_POST as $key=>$val) {
					// pass for some special entry (prefixed by 'f_')
					if(strstr($key,"f_")) continue;
					// numeric or boolean values
					elseif(is_numeric($val) || preg_match("/true|false/",$val)) fwrite($file, "\$$key = $val;\n");	
					// string values
					else fwrite($file, "\$$key = \"".preg_replace("/[\n|\r|\r\n]+/", " ", trim($val))."\";\n");
	
				}
				// save uploaded files to the "uploads" directory
				foreach ($this->podServerConfiguration->itemsConfiguration as $item){
					if ($item->type == 'file')	{
						if ($_FILES[$item->name]["error"] > 0){
							$this->error  = UPLOAD_FILE_ERROR;	
						}else{
							move_uploaded_file($_FILES[$item->name]["tmp_name"],$_SERVER['DOCUMENT_ROOT'].'/uploads/' . $item->name);
						}
					}
				}
				// close tag PHP
				fwrite($file, "?>\n");
				// close the file
				fclose($file);
				$this->status = 1;
				// reload the PodServer interface
				header("Location: /");
				break;

			// basic action to delete a file 
			case 'delete' : 
				if (isset($_POST['action_on_item']))
					{
					if (file_exists($_SERVER['DOCUMENT_ROOT'].'/uploads/' . $_POST['action_on_item']))
						{
						unlink($_SERVER['DOCUMENT_ROOT'].'/uploads/' . $_POST['action_on_item']);
						}
					}
				$this->status = 1;
				header("Location: /");
				break;

			// apply the global configuration : run all makers in "config/makers"
			case 'apply' :
				// this create a "config/files/config-XXX.conf" whith the maker found
 				$makers = glob($_SERVER['DOCUMENT_ROOT'].'/config/makers/make-config-*.php', GLOB_BRACE);
				// load the ssh PHP implementation
				set_include_path(get_include_path() . PATH_SEPARATOR . 'system/ssh');
				include('Net/SSH2.php');
				// initiate ssh console connexion with localhost 
				global $ssh;
				$ssh = new Net_SSH2('localhost');
				// login to ssh console with the root user
				if (!$ssh->login('root', $_SESSION['password'])) {
					$_SESSION['logged'] = 'error';				
				}else{
					// run all makers in the directory
					foreach($makers as $maker) {						
						$pat[0]= 'make-config-';
						$pat[1]= '.php';
						$remp[0]= '';
						$remp[1]= '';
						// get the single maker name
						$maker_name = basename($maker);
						$maker_name = str_replace($pat,$remp,$maker_name);
						// set the temp conf file for the maker
						global $config_done;
						$config_done = $_SERVER['DOCUMENT_ROOT'].'/config/files/config-'.$maker_name.'.conf';
						global $config_generated;
						global $config_syst;
						// run the maker
						include($maker);
						// create the config temp file
						if($file = @fopen($config_done, 'w')) {
							fwrite($file,$config_generated);
							fclose($file);
							// copy files to their system path to apply configuration
							$ssh->exec('cp ' . $config_done . ' ' . $config_syst);
						}								
							/* NOTE : you can add all makers you want by adding a file "config/makers/make-config-YOURMAKERNAME.php" 
							   In your maker file, you have to correctly configure 
								"$config_syst" : the conf file path in your system
							   And feed correctly the configuration 
								"$config_generated" : the content of the conf. file to write to the system
							   This action will execute it automatically when "apply" action will be used
							*/
					}
				}
				// copy uploaded files to their system directory
				foreach ($this->podServerConfiguration->itemsConfiguration as $item){
					$file_uploads = $_SERVER['DOCUMENT_ROOT'].'/uploads/' . $item->name;
					$fils_system  = $item->value . $item->name;
					if ($item->type == 'file' && $item->value != '' &&  file_exists($file_uploads) &&  file_exists($item->value) )  {
						$ssh->exec('cp ' . $file_uploads . ' ' . $fils_system);
					}
				}
				$this->status = 1;
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
				break;
		}
		return $this->status;
	}
}

?>
