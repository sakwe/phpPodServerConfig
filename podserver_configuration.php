<?php


/**
 * This is the configuration class that contains all items as follows the "config-map.php" directives
 * It get values for items from the "config-podserver.php"
 */
class PodServerConfiguration{
	
	public $itemsConfiguration;

	// instantiate the "PodServerConfiguration" (loads the config-map and get the values when construct)
	public function __construct($podServerSystem) {
		// attach to the "podServerSystem" instance
		$this->podServerSystem    = $podServerSystem;
		$this->itemsConfiguration = array();
		// load the configuration
		$this->configurationLoad();
	}

	/**
	 * Load the global configuration in the PodServer instance
	 */
	function configurationLoad() {
		global $podserver_config_map;
		$item_idx = 0;
		unset($this->itemsConfiguration);
		$this->itemsConfiguration = array();
		// load the configuration details for each item in the map
		foreach ($podserver_config_map  as $itemToConfigure){
			// get the configuration variable by indirection ($$)
			global $$itemToConfigure[0];
			// get the label for the item in the correct language by indirection ($$)
			$label_item_variable = 'label_'.$itemToConfigure[0];
			global $$label_item_variable;
			if ($$label_item_variable == '') $$label_item_variable = '$'.$label_item_variable;
			if (isset($_SESSION['lang']) && $_SESSION['lang'] == 'debug') $$label_item_variable = '$'.$label_item_variable;
			// get the help for the item in the correct language by indirection ($$)
			$help_item_variable = 'help_'.$itemToConfigure[0];
			global $$help_item_variable;
			if (isset($_SESSION['lang']) && $_SESSION['lang'] == 'debug') $$help_item_variable = '$'.$help_item_variable;
			// get the title for the item in the correct language by indirection ($$)
			$title_item_variable = 'title_'.$itemToConfigure[0];
			global $$title_item_variable;
			// show title variable name for language debug
			if (isset($_SESSION['lang']) && $_SESSION['lang'] == 'debug') $$title_item_variable = '$'.$title_item_variable;
			// get the default value from configuration map if no value found for the item in the configuration file
			if ($$itemToConfigure[0] == '') $$itemToConfigure[0] = $itemToConfigure[4];
			// load the item with all datas (map, value, title and label)
			$this->itemsConfiguration[] = new ItemConfiguration(
							$itemToConfigure[0],
							$itemToConfigure[1],
							$itemToConfigure[2],
							$itemToConfigure[3],
							$$itemToConfigure[0],
							$itemToConfigure[5],
							$$label_item_variable,
							$$title_item_variable,
							$$help_item_variable
							);			
		}
	}	


	/**
	 * Basic record action for the global configuration
	 */
	function configurationRecord() {
		// open the global configuration file that contains the variables and their values
		if(!$file = @fopen($_SERVER['DOCUMENT_ROOT'].'/config/config-podserver.php', 'w')) {
			// if some error, tell it!
			$error  = ERROR_CAN_NOT_OPEN_CONFIG_PODSERVER_PHP;
			return $error;
		}
		// open the software configuration file that contains "define"s
		if(!$file_soft = @fopen($_SERVER['DOCUMENT_ROOT'].'/config.php', 'w')) {
			// if some error, tell it!
			$error  = ERROR_CAN_NOT_OPEN_CONFIG_PODSERVER_PHP;
			return $error;
		}
		// config file header
		fwrite($file, "<?\n// ".FILE_GENERATED_BY." PodServer Configuration\n");
		fwrite($file_soft, "<?\n// ".FILE_GENERATED_BY." PodServer Configuration\n");
		// write configuration variables into the "config/config-podserver.php" file
		foreach($_POST as $key=>$val) {
			// pass for some special entry (prefixed by 'f_')
			if(strstr($key,"f_")) continue;
			// consider DEF_ item as for this software installation configuration defines
			elseif(strstr($key,"DEF_")) {
				$key = substr($key,4,strlen($key));
				fwrite($file_soft, "define(\"$key\", \"$val\");\n");
			}
			// numeric or boolean values
			elseif(is_numeric($val) || preg_match("/true|false/",$val)) fwrite($file, "\$$key = $val;\n");	
			// string values
			else fwrite($file, "\$$key = \"".preg_replace("/[\n|\r|\r\n]+/", " ", trim($val))."\";\n");

		}
		
		fwrite($file_soft,"define('PODSERVER_BAN','".addslashes(" ___   _   __   ____  ____  ___  _____   __    \\\\ || //
| _ \ (_) //\\\\ | ___||  _ \| _ ||  _  \ //\\\\    \\\\||//
|| \ \| |//  \\\\| |__ | (_)||| ||| (_) ///  \\\\ ====()====
|| | || |||__|||__  || ___/|| |||    / ||__||   //||\\\\
||_/ /| |||  || __| || |   ||_||| |\ \ ||  ||  // || \\\\
|___/ |_|||  |||____||_|   |___||_| \_\||  || ")."');");
		
		// save uploaded files to the "uploads" directory
		foreach ($this->itemsConfiguration as $item){
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
		fwrite($file_soft, "?>\n");
		// close the file
		fclose($file);
		fclose($file_soft);
		return true;
	} 


	/**
	 * Basic action to delete a file 
	 */
	function configurationDeleteFile($fileToDelete){

		if (file_exists($_SERVER['DOCUMENT_ROOT'].'/uploads/' . $fileToDelete))
			{
			unlink($_SERVER['DOCUMENT_ROOT'].'/uploads/' . $fileToDelete);
			}				
	}

	/**
	 * apply the global configuration : run all makers in "config/makers"
	 */
	function configurationApply(){
		// this create a "config/files/config-XXX.conf" whith the maker found
		$makers = glob($_SERVER['DOCUMENT_ROOT'].'/config/makers/make-config-*.php', GLOB_BRACE);
		
		// get an unique ID for this apply action
		$actionId = uniqid();

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
			global $config_user_do;
			global $config_syst;
			// run the maker
			include($maker);
			// create the config temp file
			if($file = @fopen($config_done, 'w')) {
				fwrite($file,$config_generated);
				fclose($file);
				// add the task to the "podServerSystem" queue (copy files to their system path to apply configuration)
				$this->podServerSystem->addTask($config_user_do,'cp ' . $config_done . ' ' . $config_syst,TASK_COPY_FILE_CONFIG,$actionId);
											
				/* NOTE : you can add all makers you want by adding a file "config/makers/make-config-YOURMAKERNAME.php" 
				   In your maker file, you have to correctly configure 
					"$config_syst"    : the conf file path in your system
					"$config_user_do" : the user that copy the configuration system file (for "podServerSystem" tasks queue)
				   And feed correctly the configuration 
					"$config_generated" : the content of the conf. file to write to the system
				   This action will execute it automatically when "apply" action will be used
				*/
			}
		}
		
		// copy uploaded files items of the global configuration to their system directory
		foreach ($this->itemsConfiguration as $item){
			if ($item->type == 'file') {
				$file_uploads = $_SERVER['DOCUMENT_ROOT'].'/uploads/' . $item->name;
				// if the directory does not exists, create it by adding a "mkdir" task for the system user
				if (!file_exists($item->values)) {
					$this->podServerSystem->addTask($item->value,'mkdir ' . $item->values,TASK_MKDIR_AUTO);
				}
				$file_system  = $item->values . $item->name;
				// adding a "cp" task for the system user			
				if ($item->values != '' &&  file_exists($file_uploads) )  {
					$this->podServerSystem->addTask($item->value,'cp ' . $file_uploads . ' ' . $file_system,TASK_COPY_FILE_ITEM,$actionId);
				}
			}
		}
	}
}

/**
 * This is a configuration item class
 */
class ItemConfiguration{

	public $name,$group,$depend,$type,$value,$values,$label,$title;

	// put values for the item when construct
	public function __construct($name,$group,$depend,$type,$value,$values,$label,$title,$help) {
		$this->name	= $name;
		$this->group	= $group;
		$this->depend	= $depend;
		$this->type	= $type;
		$this->value	= $value;
		$this->values	= $values;
		$this->label	= $label;
		$this->title	= $title;
		$this->help	= $help;
	}

	// generate the HTML code to render the item
	public function getHTML() {
		$html = '';
		if ($this->type != 'title') $html.= '<p>'."\n";
		if ($this->type != 'info' && $this->type != 'system' && $this->type != 'title')  
			{			
			$html.= '<label id="label_for_'. $this->name . '" name="label_for_'. $this->name . '" for="'. $this->name . '">';
			// special check for files item
			if ($this->type == 'file')
				{
				if (file_exists($_SERVER['DOCUMENT_ROOT'].'/uploads/' . $this->name))
					{
					$html.= '<a href = "/uploads/'. $this->name .'">'.$this->label.'</a> (<a href="javascript:void Messi.ask(\''.DELETE.' ' .$this->name.' ?\', function(val) { if(val==\'Y\') formSubmit(\'delete\',\'' . $this->name . '\');});">'.DELETE.'</a>)';
					}
				else
					{
					$html.= $this->label.' ('.NONE.')';
					}
				}
			else
				{
				$html.= $this->label;
				}
			$html.= '</label>'."\n";
			}
		// add dependence of the item for js managing in user interface
		if ($this->depend != 'none' or $this->depend != '') {
			$html.= '<input type="hidden" id="dependance_for_'. $this->name . '" value="'. $this->depend . '" />'."\n";
		}	
		$html.= '<input type="hidden" id="title_for_'. $this->name . '" value="'. $this->title . '" />'."\n";
		switch ($this->type)
			{
			case 'text' : 
				$html.= '<input id="'. $this->name . '" name="'. $this->name . '" value="'. $this->value . '" onchange="display_dependance();" />'."\n";
				break;

			case 'password' : 
				$html.= '<input type="password" id="'. $this->name . '" name="'. $this->name . '" value="'. $this->value . '" onchange="display_dependance();" />'."\n";
				break;

			case 'checkbox' : 
				$html.= '<input type="checkbox" id="'. $this->name . '" name="'. $this->name . '"' . ( ($this->value == 'on') ? " checked " : "") . ' onchange="display_dependance();" />'."\n";
				break;

			case 'file' : 
				$html.= '<input type="file" id="'. $this->name . '" name="'. $this->name . '" value="'. $this->value . '" />'."\n";
				break;

			case 'select' :
				$html.= '<select id="'. $this->name . '" name="'. $this->name . '" onchange="display_dependance();" >'."\n";
				$valuesPossible = explode(',',$this->values);
				foreach ($valuesPossible as $valuePossible){
					$html.= '<option value="'. $valuePossible . '" ' . (($valuePossible == $this->value) ? " selected " : "") . '>'. $valuePossible . '</option>'."\n";
					}
				$html.= '</select><div style="clear:both"></div>'."\n";
				break; 

			case 'title' : 
				$html.= "\n". '<h2>'.$this->label.'</h2><hr />'."\n\n";			
				break;

			// system action items
			case 'system' : 
				// a system item will render a button that submit the form with the configured action name in the config-map.php
				$html.= '<label></label><input class="button" type="button" id="'. $this->name . '" name="'. $this->name . '" value="'.$this->label.'" 
				onclick="new Messi(\''.addslashes($this->help).'\', {title: \''.addslashes($this->label).'?\', modal: true, buttons: [{id: 0, label: \''.YES.'\', val: \'Y\'}, {id: 1, label: \''.NO.'\', val: \'N\'}], callback: function(val) { if(val==\'Y\') formSubmit(\''.$this->values.'\');}});" />';
				break;

			case 'html' : 
				// this is only arbitrary html code to display
				$html.= $this->value;			
				break;

			/**
			 * HERE YOU CAN ADD THE TYPE RENDERER YOU NEED
			 *
			 * case 'YOUR_TYPE' : 
			 *	$html.= 'ANY_HTML_CODE_TO_RENDER';
			 *	break;
			 */

			default : 
				$html.= '<b>'.NO_METHOD_TO_RENDER_THIS_ITEM .'</b><br />Item : '.$this->name. '<br />Type : '.$this->type. '<br />'."\n"; 
				break;
			
			}
		if ($this->type != 'title') $html.= '</p>'."\n"; 		
		return $html;
	}	
}


?>
