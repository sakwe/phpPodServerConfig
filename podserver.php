<?php

// get the map for items to manage in PodServer configuration
include($_SERVER['DOCUMENT_ROOT'].'/config/config-map.php'); 

// include configuration and dispatch classes needed by PodServer class
include($_SERVER['DOCUMENT_ROOT'].'/podserver_configuration.php'); 
include($_SERVER['DOCUMENT_ROOT'].'/podserver_dispatcher.php'); 


// the main object to instanciate : "PodServer"
class PodServer {
	
	public $podServerConfiguration;
	public $actiondispatcher;

	// attach and load the "PodServerConfiguration" and "Actiondispatcher" for the "PodServer"
	public function __construct() {
		$this->podServerConfiguration = new PodServerConfiguration();
		$this->actiondispatcher = new Actiondispatcher();
	}
	
	// delegate "actions" to the "Actiondispatcher"
	public function actionDispatch($action) {
		return $this->actiondispatcher->execute($action);
	}

	// generate the HTML code to render the configuration tabs
	public function getInterface() {
		$actualTab = '';
		$tabs_headers = '<ol id="toc">'."\n";
		$js_tabs_loading = '';
		$tabs_content = '';
		// create tabs and add each item configuration into the right tab
		foreach ($this->podServerConfiguration->itemsConfiguration as $item){
			if ($item->group != $actualTab)	{
				$tab_idx++;
				if ($actualTab != '') $tabs_content.= '</div>'."\n";
				// tabs header
				$label_tab_variable = 'label_tab_'.$item->group;
				global $$label_tab_variable;
				if ($$label_tab_variable == '') $$label_tab_variable = 'label_tab_'.$item->group;
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
		// include the js script to manage submit form with actions
		$interface=	   '<script>'."\n".'
					function formSubmit(action){'."\n".'
					document.getElementById("action").value=action;'."\n".'
					document.getElementById("podserver").submit();'."\n".'
				  }</script>'."\n";
		// assembly the global interface 		
		$interface.='<form method="post" id="podserver" action="">'."\n".
			'<input type="hidden" name="action" id="action" value="" />'."\n".
			$this->getTitle() .
			$this->getStatus() .
			'<div class="tabs">'."\n".$tabs_headers."\n".$tabs_content."\n".$js_tabs_loading."\n".'</div>'."\n".
			$this->getFooter();
			'</form>'."\n";
		return $interface;
	}

	public function getTitle(){
		return '<h1>'.BIG_TITLE_POD_CONFIGURATION.'</h1>';

	}

	public function getStatus(){
		return '<iframe src="system/system-status.php" scrolling="auto"></iframe>';

	}

	public function getFooter(){
		$footer = '';
		if ($this->actiondispatcher->status == 0) $footer.= $this->actiondispatcher->error;
		$footer.= '<p><label>&nbsp;</label><input id="record" type="button" value="'.RECORD_BUTTON_LABBEL.'" onclick="formSubmit(\'record\');" /></p>';
		return $footer;
	}


}


?>
