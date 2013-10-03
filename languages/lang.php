<?php

// What languages do we support
$available_langs = array('en','fr','de');

// Set our default language session
$_SESSION['lang'] = 'fr';   

if(isset($_GET['lang']) && $_GET['lang'] != ''){ 
	// check if the language is one we support
	if(in_array($_GET['lang'], $available_langs)){       
		$_SESSION['lang'] = $_GET['lang']; // Set session
	}
}
// Include active language
include($_SERVER['DOCUMENT_ROOT'].'/languages/'.$_SESSION['lang'].'/lang.'.$_SESSION['lang'].'.php');

?>
