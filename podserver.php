<?php

// get the map for items to manage in PodServer configuration
include($_SERVER['DOCUMENT_ROOT'].'/config/config-map.php'); 

// include configuration and dispatch classes needed by PodServer class
include($_SERVER['DOCUMENT_ROOT'].'/podserver_configuration.php'); 
include($_SERVER['DOCUMENT_ROOT'].'/podserver_system.php'); 
include($_SERVER['DOCUMENT_ROOT'].'/podserver_dispatcher.php'); 


/** 
 * The main object to instanciate : "PodServer"
 */
class PodServer {
	
	public $logged;
	public $podServerConfiguration;
	public $podServerSystem;
	public $podServerDispatcher;

	// attach and load the "PodServerConfiguration" and "PodServerDispatcher" for the "PodServer"
	public function __construct() {
		$this->podServerSystem		= new PodServerSystem();
		$this->podServerConfiguration	= new PodServerConfiguration($this->podServerSystem);
		$this->podServerDispatcher	= new PodServerDispatcher($this->podServerConfiguration);
	}
	
	// delegate "actions" to the "Actiondispatcher"
	public function actionDispatch($action,$target='/') {
		return $this->podServerDispatcher->execute($action,$target);
	}

	// generate the HTML code to render the PodServer configuration interface
	public function getInterface() {		
		// assembly the global interface 
		$interface.= $this->getTitle();
		// ask to log in if not done for SYST_USER_FOR_DIASPORA
		if (!$this->podServerSystem->sessions[$this->podServerSystem->getSessionIdx(SYST_USER_FOR_DIASPORA)]->userLogged){
			$interface.= $this->getAuth();

		}else{	
			$interface.= $this->getMonitor();
			$interface.= $this->getTabs();
			$interface.= $this->getFooter();
		}
		return $this->getForm($interface);
	}

	public function getForm($content,$action="") {
		$form = '<form method="post" id="podserver" action="'.$action.'" enctype="multipart/form-data">'."\n".
			'<input type="hidden" name="action" id="action" value="" />'."\n".
			'<input type="hidden" name="action_on_item" id="action_on_item" value="" />'."\n";

		$form.= $content;

		// include the js script to manage submit form with actions
		$form.= '<script>'."\n".'
			function formSubmit(action,action_on_item){'."\n".'
			document.getElementById("action").value=action;'."\n".'
			document.getElementById("action_on_item").value=action_on_item;'."\n".'
			document.getElementById("podserver").submit();'."\n".'
			 }</script>'."\n";
		// close and send the form
		$form.= '</form>'."\n";
		return $form;
	}

	// it idsplay the title top zone
	public function getTitle(){
		return '<div class="header_bar">'.BIG_TITLE_POD_CONFIGURATION.'</div>';

	}

	// it disply the auth login interface from the "PodServerSystem" class
	public function getAuth(){
		// ask the "PodServerSystem" class to render a autentication interface
		return $this->podServerSystem->getAuth();		
	}

	public function getMonitor(){
		// include the monitor iframe that use js to refesh status of services and tasks.
		return '<iframe id="iframe_monitor" name="iframe_monitor" src="system/system-monitor.php" scrolling="auto"></iframe>';

	}

	// it render the tabs that contains the global configuration items
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
				$tabs_headers.= '<li><a href="#'.$item->group.'" id="tab_'.$item->group.'" name="tab_'.$item->group.'"><span>'.$$label_tab_variable.'</span></a></li>'."\n";
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

	// it render the footer with "disconnect" and "record" option
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
