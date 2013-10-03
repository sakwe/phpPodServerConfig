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
			// get the configuration variable by indirection
			global $$itemToConfigure[0];
			// get the label for the item in the correct language by indirection
			$label_item_variable = 'label_'.$itemToConfigure[0];
			global $$label_item_variable;
			if ($$label_item_variable == '') $$label_item_variable = 'label_'.$itemToConfigure[0];
			// get the default value from configuration map if no value found for the item in the configuration file
			if ($$itemToConfigure[0] == '') $$itemToConfigure[0] = $itemToConfigure[4];
			// load the item with all datas (map, value and label)
			$this->itemsConfiguration[] = new ItemConfiguration(
							$itemToConfigure[0],
							$itemToConfigure[1],
							$itemToConfigure[2],
							$itemToConfigure[3],
							$$itemToConfigure[0],
							$itemToConfigure[5],
							$$label_item_variable
							);			
		}
	}	 
}

// this is a configuration item class
class ItemConfiguration{

	public $name,$group,$depend,$type,$value,$values,$label;

	// put values for the item when construct
	public function __construct($name,$group,$depend,$type,$value,$values,$label) {
		$this->name = $name;
		$this->group = $group;
		$this->depend = $depend;
		$this->type = $type;
		$this->value = $value;
		$this->values = $values;
		$this->label = $label;
	}

	// generate the HTML code to render the item
	public function getHTML() {
		$html = '';
		if ($this->type != 'title') $html.= '<p>'."\n";
		if ($this->type != 'info' && $this->type != 'system' && $this->type != 'title')  $html.= '<label for="'. $this->name . '">' . $this->label . '</label>'."\n";
		switch ($this->type)
			{
			case 'text' : 
				$html.= '<input id="'. $this->name . '" name="'. $this->name . '" value="'. $this->value . '" />'."\n";
				break;

			case 'password' : 
				$html.= '<input type="password" id="'. $this->name . '" name="'. $this->name . '" value="'. $this->value . '" />'."\n";
				break;

			case 'checkbox' : 
				$html.= '<input type="checkbox" id="'. $this->name . '" name="'. $this->name . '" value="'. $this->value . '" />'."\n";
				break;

			case 'file' : 
				$html.= '<input type="file" id="'. $this->name . '" name="'. $this->name . '" value="'. $this->value . '" />'."\n";
				break;

			case 'select' :
				$html.= '<select id="'. $this->name . '" name="'. $this->name . '">'."\n";
				$valuesPossible = explode(',',$this->values);
				foreach ($valuesPossible as $valuePossible){
					$html.= '<option value="'. $valuePossible . '" ' . (($valuePossible == $this->value) ? " selected " : "") . '>'. $valuePossible . '</option>'."\n";
					}
				$html.= '</select>'."\n";
				break; 

			case 'title' : 
				$html.= "\n". '<h2>'.$this->label.'</h2><hr />'."\n\n";			
				break;

			case 'info' : 
				$html.= $this->value;			
				break;

			case 'system' : 
				$html.= '<label></label><input type="button" value="'.$this->label.'" onclick="formSubmit(\''.$this->values.'\');" />';			
				break;

			/*
			HERE YOU CAN ADD TYPE RENDERER YOU NEED
			
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
