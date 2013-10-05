<?php

// get the language for the interface
include($_SERVER['DOCUMENT_ROOT'].'/languages/lang.php');

?>

function display_dependance() 
	{
	var inputs = document.getElementById("podserver").getElementsByTagName('*');
	for (var i = 0; i < inputs.length; i++)
		{
		if (inputs[i].id.substr(0,15) == "dependance_for_")
			{
			var disable = false;
			var item = inputs[i].id;
			var action_to_do = '';
			item = item.replace("dependance_for_",'');
			if (document.getElementById(item)) 
				{
				if (inputs[i].value != 'none' && inputs[i].value != '' )
					{
					switch (document.getElementById(inputs[i].value).type)
						{
						case 'text' : 
							action_to_do = '<?= ACTION_TO_DO_CATCH_FIELD ?>';
							if (document.getElementById(inputs[i].value).value == '') disable = true;
							break;

						case 'checkbox' : 
							action_to_do = '<?= ACTION_TO_DO_CHECK_FIELD ?>';
							if (document.getElementById(inputs[i].value).checked == false) disable = true;
							break;

						case 'password' : 
							action_to_do = '<?= ACTION_TO_DO_CATCH_FIELD ?>';
							if (document.getElementById(inputs[i].value).value == '') disable = true;
							break;

						default : 
							action_to_do = '<?= ACTION_TO_DO_SELECT_FIELD ?>';
							if (document.getElementById(inputs[i].value).value == 'none') disable = true;
							break;
						}
					}			
				document.getElementById(item).disabled = disable;
				if (disable)
					{
					if (document.getElementById("label_for_"+inputs[i].value))
						{
						document.getElementById(item).title = action_to_do + ' ' + document.getElementById("label_for_"+inputs[i].value).innerHTML + ' ' + '<?= TO_ACCESS_THIS_FIELD ?>';
						}
					}
				else
					{
					if (document.getElementById('title_for_'+item))
						{
						document.getElementById(item).title = document.getElementById('title_for_'+item).value;						
						}
					else 		
						{
						document.getElementById(item).title = '';
						}
					}
				//document.getElementById(item).label.style.display = "none";
				}
			}
		}
	}


display_dependance() ;
