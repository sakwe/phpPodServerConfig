<?php

// get the map for items to manage in PodServer configuration
include($_SERVER['DOCUMENT_ROOT'].'/config/config-map.php'); 

// include configuration and dispatch classes needed by PodServer class
include($_SERVER['DOCUMENT_ROOT'].'/podserver_configuration.php'); 
include($_SERVER['DOCUMENT_ROOT'].'/podserver_dispatcher.php'); 


// the main object to instanciate : "PodServer"
class PodServer {
	
	public $logged;
	public $podServerConfiguration;
	public $actiondispatcher;

	// attach and load the "PodServerConfiguration" and "Actiondispatcher" for the "PodServer"
	public function __construct() {
		if (!isset($_SESSION['logged'])) $_SESSION['logged'] = 'false';
		$this->logged = $_SESSION['logged'];
		$this->podServerConfiguration = new PodServerConfiguration();
		$this->actiondispatcher = new Actiondispatcher($this->podServerConfiguration);
	}
	
	// delegate "actions" to the "Actiondispatcher"
	public function actionDispatch($action) {
		return $this->actiondispatcher->execute($action);
	}

	// generate the HTML code to render the configuration tabs
	public function getInterface() {		
		// assembly the global interface 		
		$interface ='<form method="post" id="podserver" action="" enctype="multipart/form-data">'."\n".
			'<input type="hidden" name="action" id="action" value="" />'."\n".
			'<input type="hidden" name="action_on_item" id="action_on_item" value="" />'."\n";

		$interface.= $this->getTitle();
		// ask to log in if not done
		if ($this->logged != 'true'){
			$interface.= $this->getAuth();
		}else{	
			$interface.= $this->getMonitor();
			$interface.= $this->getTabs();
			$interface.= $this->getFooter();
		}

		// include the js script to manage submit form with actions
		$interface.='<script>'."\n".'
			function formSubmit(action,action_on_item){'."\n".'
			document.getElementById("action").value=action;'."\n".'
			document.getElementById("action_on_item").value=action_on_item;'."\n".'
			document.getElementById("podserver").submit();'."\n".'
			 }</script>'."\n";
		// close and send the form
		$interface.= '</form>'."\n";
		return $interface;
	}

	public function getTitle(){
		return '<div class="header_bar">'.BIG_TITLE_POD_CONFIGURATION.'</div>';

	}

	public function getAuth(){
		$authentication = '';
		if ($this->logged == 'error') $authentication.= '<h2>'.ERROR_SSH_LOGIN_TRY_AGAIN.'</h2><hr />';
		else $authentication.= '<h2>'.ENTER_PASSWORD_FOR_USER.' '.USER_FOR_DIASPORA_AND_SUDOER.'</h2><hr />';
		$authentication.= '<label>Password :</label><input type="password" name="password" id="password" /><br/>
				<input type="submit" value="'.BUTTON_LOGIN.'" onclick="formSubmit(\'login\');" /><br />';
		return $authentication;
	}

	public function getMonitor(){
		// include the monitor iframe that use js to refesh state of service : principaly apache.
		return '<iframe src="system/system-monitor.php" scrolling="auto"></iframe>';

	}

	public function getTabs(){
		$actualTab = '';
		$tabs_headers = '<ol id="toc">'."\n";
		$js_tabs_loading = '';
		$tabs_content = '';
		// create tabs and add each item configuration into the right tab
		foreach ($this->podServerConfiguration->itemsConfiguration as $item){
			if ($item->group != $actualTab)	{
				if ($actualTab != '') $tabs_content.= '</div>'."\n";
				// tabs header
				$label_tab_variable = 'label_tab_'.$item->group;
				global $$label_tab_variable;
				if ($$label_tab_variable == '') $$label_tab_variable = '$'.$label_tab_variable;
				$tabs_headers.= '<li><a href="#'.$item->group.'"><span>'.$$label_tab_variable.'</span></a></li>'."\n";
				// tabs js loading code
				if ($js_tabs_loading != '') $js_tabs_loading.=', ';			
				$js_tabs_loading.='\''.$item->group.'\'';
				// tabs content
				$tabs_content.= '<div class="content" id="'.$item->group.'">'."\n";
			}
			// render the item and add it to the current tab
			$tabs_content.= $item->getHTML();
			$actualTab = $item->group;
		}
		$tabs_content.= '</div>'."\n";
		$tabs_headers.= '</ol>'."\n";
		
		// include the js script to manage tabs
		$js_tabs_loading = '<script src="js/activatables.js" type="text/javascript"></script>'."\n".
				   '<script type="text/javascript">'."\n".'activatables(\'tab\', [' . $js_tabs_loading . ']);'."\n".'</script>'."\n";

		// include the script that manage dependances
		$js_item_dependance = '<script src="js/dependances.php';
		if(isset($_GET['lang']) && $_GET['lang'] != '') $js_item_dependance.='?lang='.$_GET['lang'];
		$js_item_dependance.='" type="text/javascript"></script>'."\n";		

		// finalyse the config tabs area
		$tabs = '<div class="tabs">'."\n".$tabs_headers."\n".$tabs_content."\n".'</div>'."\n".$js_tabs_loading."\n".$js_item_dependance."\n";		
		return $tabs;
		
	}

	public function getFooter(){
		$footer = '';
		if ($this->actiondispatcher->status == 0) $footer.= $this->actiondispatcher->error;
		$footer.= '<p>	<input type="submit" value="'.BUTTON_LOGOUT.'" onclick="formSubmit(\'logout\');" />
				<input id="record" type="button" value="'.BUTTON_RECORD.'" onclick="formSubmit(\'record\');" />
			</p>';
		return $footer;
	}


}


?>
