<?php
// files path
global $config_syst;
$config_syst = FILE_APACHE_VHOST;
$config_user_do = 'root';
//---------------------------------------------------------------------

// get the PodServer global configuration
include($_SERVER['DOCUMENT_ROOT'].'/config/config-podserver.php');
//----------------------------------------------------------------


// clean the config variable
global $config_generated;
$config_generated = "";
//-----------------------------


$config_generated.= "<VirtualHost *:80> \n";
$config_generated.= "ServerName " . $domain_name . " \n";
if ($ssl_enable == 'on')
	{
	$config_generated.= "RedirectPermanent / https://" . $domain_name . "/ \n";
	$config_generated.= "</VirtualHost> \n";
	$config_generated.= "<VirtualHost *:443> \n";
	$config_generated.= "ServerName " . $domain_name . " \n";
	}
$config_generated.= "
DocumentRoot ".DIRECTORY_DIASPORA."public\n
\n
RewriteEngine On
\n
RewriteCond %{DOCUMENT_ROOT}/%{REQUEST_FILENAME} !-f
RewriteRule ^/(.*)$ balancer://upstream%{REQUEST_URI} [P,QSA,L]\n
 \n
<Proxy balancer://upstream>\n
BalancerMember http://127.0.0.1:".$pod_port."\n
</Proxy>\n
 \n
ProxyRequests Off\n
ProxyVia On\n
ProxyPreserveHost On\n
RequestHeader set X_FORWARDED_PROTO https\n
\n
<Proxy *>\n
Order allow,deny\n
Allow from all\n
</Proxy>\n
\n
<Directory ".DIRECTORY_DIASPORA."public>\n
Allow from all\n
AllowOverride all\n
Options -MultiViews\n
</Directory>\n
\n
";

if ($ssl_enable == 'on')
	{
	$config_generated.= "SSLEngine On \n";
	$config_generated.= "SSLCertificateFile ".DIRECTORY_SSL_CERTIFICATE.$domain_name."/ssl_cert \n";
	$config_generated.= "SSLCertificateKeyFile ".DIRECTORY_SSL_CERTIFICATE.$domain_name."/ssl_key \n";
	$config_generated.= "SSLCertificateChainFile ".DIRECTORY_SSL_CERTIFICATE.$domain_name."/ssl_ca \n";
	}

$config_generated.= "</VirtualHost> \n";



// $config_generated and $config_syst are ok ? ready to go !

?>
