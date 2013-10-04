<?php
// files path
global $config_syst;
$config_syst = FILE_DYNDNS;
//---------------------------------------------------------------------

// get the PodServer global configuration
include($_SERVER['DOCUMENT_ROOT'].'/config/config-podserver.php');
//----------------------------------------------------------------


// clean the config variable
global $config_generated;
$config_generated = "";
//-----------------------------



// $config_generated and $config_syst are ok ? ready to go !

?>
