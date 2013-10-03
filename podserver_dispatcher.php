<?php

class Actiondispatcher{
	public $action, $status, $error;

	public function __construct() {
		$this->action = 'none';
		$this->status = 0; // 0 = unknown 1 = done 		
		$this->error  = '';
	} 
	
	public function execute($action){
		$this->action = $action;
		switch ($this->action){
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
				// write configuration variables into the file
				foreach($_POST as $key=>$val) {
					// pass for some special entry (prefixed by 'f_')
					if(strstr($key,"f_")) continue;
					// numeric or boolean values
					elseif(is_numeric($val) || preg_match("/true|false/",$val)) fwrite($file, "\$$key = $val;\n");	
					// string values
					elseif(!empty($val)) fwrite($file, "\$$key = \"".preg_replace("/[\n|\r|\r\n]+/", " ", trim($val))."\";\n");
	
				}
				// close tag PHP
				fwrite($file, "?>\n");
				// close the file
				fclose($file);
				$this->status = 1;
				// reload the PodServer interface
				header("Location: /");
				break;

			// apply the global configuration : run all makers in "config/makers"
			case 'apply' :
 				$makers = glob($_SERVER['DOCUMENT_ROOT'].'/config/makers/make-config-*.php', GLOB_BRACE);
				foreach($makers as $maker) {
					// this create a "config/files/config-XXX.conf" whith the maker found
					include($maker);
					// execute the system copy files to apply configuration
					include ($_SERVER['DOCUMENT_ROOT'].'/system/actions/exec_copy_config.php');
					/* NOTE : you can add all makers you want by adding a file "config/makers/make-config-YOURMAKERNAME.php" 
				           In your maker file, you have to correctly configure 
						"$config_done" : temp conf file in PodServer
						"$config_syst" : the conf file in your system
					   This action will execute it automatically when "apply" action will be used
					*/
				}
				$this->status = 1;
				break;

			// run the system script associated to the current action
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
