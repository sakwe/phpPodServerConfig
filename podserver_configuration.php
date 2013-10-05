<?php


// this is the configuration class that contains all items as follows the "config-map.php" directives
class PodServerConfiguration{
	
	public $itemsConfiguration;

	// instanciate the "PodServerConfiguration" (loads the conf map and get the values)
	public function __construct() {
		$this->itemsConfiguration = array();
		$this->configurationLoad();
	}

	function configurationLoad() {
		global $podserver_config_map;
		$item_idx = 0;
		// load the configuration details for each item in the map
		foreach ($podserver_config_map  as $itemToConfigure){
			// get the configuration variable by indirection ($$)
			global $$itemToConfigure[0];
			// get the label for the item in the correct language by indirection ($$)
			$label_item_variable = 'label_'.$itemToConfigure[0];
			global $$label_item_variable;
			if ($$label_item_variable == '') $$label_item_variable = '$'.$label_item_variable;
			if (isset($_GET['lang']) && $_GET['lang'] == 'debug') $$label_item_variable = '$'.$label_item_variable;
			// get the title for the item in the correct language by indirection ($$)
			$title_item_variable = 'title_'.$itemToConfigure[0];
			global $$title_item_variable;
			// show title variable name for langauge debug
			if (isset($_GET['lang']) && $_GET['lang'] == 'debug') $$title_item_variable = '$'.$title_item_variable;
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
							$$title_item_variable
							);			
		}
	}	 
}

// this is a configuration item class
class ItemConfiguration{

	public $name,$group,$depend,$type,$value,$values,$label,$title;

	// put values for the item when construct
	public function __construct($name,$group,$depend,$type,$value,$values,$label,$title) {
		$this->name = $name;
		$this->group = $group;
		$this->depend = $depend;
		$this->type = $type;
		$this->value = $value;
		$this->values = $values;
		$this->label = $label;
		$this->title = $title;
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
					$html.= '<a href = "/upaloads/'. $this->name .'">'.$this->label.'</a> (<a href="javascript:formSubmit(\'delete\',\'' . $this->name . '\');">'.DELETE.'</a>)';
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
		// add dependance of the item for js managing in user interface
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
				$html.= '</select>'."\n";
				break; 

			case 'title' : 
				$html.= "\n". '<h2>'.$this->label.'</h2><hr />'."\n\n";			
				break;

			case 'system' : 
				// a system item will render a button that submit the form with the configured action name in the config-map.php
				$html.= '<label></label><input class="button" type="button" id="'. $this->name . '" name="'. $this->name . '" value="'.$this->label.'" onclick="formSubmit(\''.$this->values.'\');" />';			
				break;

			case 'html' : 
				// this is only arbitrary html code to display
				$html.= $this->value;			
				break;

			/*
			HERE YOU CAN ADD THE TYPE RENDERER YOU NEED
			
			case 'YOUR_TYPE' : 
				$html.= 'ANY_HTML_CODE_TO_RENDER';
				break;
			*/

			default : 
				$html.= '<b>'.NO_METHOD_TO_RENDER_THIS_ITEM .'</b><br />Item : '.$this->name. '<br />Type : '.$this->type;
				break;
			
			}
		if ($this->type != 'title') $html.= '</p>'."\n"; 		
		return $html;
	}	
}


?>
