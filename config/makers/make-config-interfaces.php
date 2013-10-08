<?php
// files path
global $config_syst;
$config_syst = FILE_NETWORK_INTERFACES;
$config_user_do = 'root';
//---------------------------------------------------------------------

// get the PodServer global configuration
include($_SERVER['DOCUMENT_ROOT'].'/config/config-podserver.php');
//----------------------------------------------------------------


// clean the config variable
global $config_generated;
$config_generated = "";
//-----------------------------


$config_generated.= "# This file describes the network interfaces available on your system\n";
$config_generated.= "# # and how to activate them. For more information, see interfaces(5).\n";
$config_generated.= "\n";
$config_generated.= "# The loopback network interface\n";
$config_generated.= "auto lo \n";
$config_generated.= "iface lo inet loopback \n";
$config_generated.= " \n";
$config_generated.= "# The primary network interface \n";
$config_generated.= "allow-hotplug eth0 \n";
$config_generated.= "auto eth0 \n";
$config_generated.= "iface eth0 inet static \n";
$config_generated.= "address " . $local_ip . " \n";
$config_generated.= "netmask 255.255.255.0 \n";
$config_generated.= "gateway " . $gateway . " \n";





// $config_generated and $config_syst are ok ? ready to go !

?>
