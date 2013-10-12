<?php

// What languages do we support
$available_langs = array('en','fr','debug');

// Define default language.
$GLOBALS['_DLANG']='en';

// Define all available languages.
// WARNING: uncomment all available languages


// all languages that could exists... if not in supported language, we have to create a file and a directory for it translation
$GLOBALS['_LANG'] = array(
'af', // afrikaans.
'ar', // arabic.
'bg', // bulgarian.
'ca', // catalan.
'cs', // czech.
'da', // danish.
'de', // german.
'el', // greek.
'en', // english.
'es', // spanish.
'et', // estonian.
'fi', // finnish.
'fr', // french.
'gl', // galician.
'he', // hebrew.
'hi', // hindi.
'hr', // croatian.
'hu', // hungarian.
'id', // indonesian.
'it', // italian.
'ja', // japanese.
'ko', // korean.
'ka', // georgian.
'lt', // lithuanian.
'lv', // latvian.
'ms', // malay.
'nl', // dutch.
'no', // norwegian.
'pl', // polish.
'pt', // portuguese.
'ro', // romanian.
'ru', // russian.
'sk', // slovak.
'sl', // slovenian.
'sq', // albanian.
'sr', // serbian.
'sv', // swedish.
'th', // thai.
'tr', // turkish.
'uk', // ukrainian.
'zh' // chinese.
);

if(PODSERVER_LANGUAGE == 'auto'){ 
	// Set our default language session
	$_SESSION['lang'] = detect_lang();

	// check if the language is one we support
	if(!in_array($_SESSION['lang'], $available_langs)){
		$_SESSION['lang'] = $GLOBALS['_DLANG']; 
	}
}
else{
	$_SESSION['lang']= PODSERVER_LANGUAGE;
}
// Include active language
include($_SERVER['DOCUMENT_ROOT'].'/languages/'.$_SESSION['lang'].'/lang.'.$_SESSION['lang'].'.php');


// function to detect the language to use for client browwser
function detect_lang()
{
     // Detect HTTP_ACCEPT_LANGUAGE & HTTP_USER_AGENT.
     getenv('HTTP_ACCEPT_LANGUAGE');
     getenv('HTTP_USER_AGENT');

     $_AL=strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']);
     $_UA=strtolower($_SERVER['HTTP_USER_AGENT']);

     // Try to detect Primary language if several languages are accepted.
     foreach($GLOBALS['_LANG'] as $K)
     {
         if(strpos($_AL, $K)===0)
         return $K;
     }

     // Try to detect any language if not yet detected.
     foreach($GLOBALS['_LANG'] as $K)
     {
         if(strpos($_AL, $K)!==false)
         return $K;
     }
     foreach($GLOBALS['_LANG'] as $K)
     {
         //if(preg_match("/[[( ]{$K}[;,_-)]/",$_UA)) // matching other letters (create an error for seo spyder)
         return $K;
     }

     // Return default language if language is not yet detected.
     return $GLOBALS['_DLANG'];
}




?>
