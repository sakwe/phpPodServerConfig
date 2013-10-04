<?php
// set your diaspora system user
define('SYST_USER_FOR_DIASPORA','diaspora');

// set your diaspora directory
define('DIRECTORY_DIASPORA','/home/diaspora/diaspora/');

// set your ssl directory
define('DIRECTORY_SSL_CERTIFICATE','/home/diaspora/certificate/');

// set the path to the network interfaces file
define('FILE_NETWORK_INTERFACES','/etc/network/interfaces');

// set the path to the vhost file for the diaspora virtual server in apache
define('FILE_APACHE_VHOST','/etc/apache2/sites-available/vhost-diaspora.conf');

// set the path to the dyndns script 
define('FILE_DYNDNS','/home/diaspora/dyndns/dynhost');

?>
