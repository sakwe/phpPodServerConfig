<?php
// files path
global $config_syst;
$config_syst = DIRECTORY_DIASPORA.'/config/diaspora.yml';
//---------------------------------------------------------------------

// get the PodServer global configuration
include($_SERVER['DOCUMENT_ROOT'].'/config/config-podserver.php');
//----------------------------------------------------------------


// clean the config variable
global $config_generated;
$config_generated = "";
//-----------------------------

$config_generated.="configuration: ## Section\n";
$config_generated.=" \n";
$config_generated.="  environment: ## Section\n";
$config_generated.=" \n";
$config_generated.="    url: '" . (($ssl_enable == 'on') ? "https" : "http") . "://" . $domain_name . "/'\n";
$config_generated.="    certificate_authorities: '/etc/ssl/certs/ca-certificates.crt' \n";
if ($ssl_enable == 'on') $config_generated.="    require_ssl: true \n";
$config_generated.=" \n";

$config_generated.="    sidekiq: ## Section\n";
$config_generated.=" \n";
$config_generated.="    s3: ## Section \n";
$config_generated.=" \n";
$config_generated.="    assets: ## Section \n";
$config_generated.=" \n";

$config_generated.="  server: ## Section\n";
$config_generated.="    port: " . $pod_port . " \n";
$config_generated.="    rails_environment: 'production' \n";
$config_generated.=" \n";

$config_generated.="  privacy: ## Section\n";
if ($jquery == 'on') $config_generated.="    jquery_cdn: true \n";
if(!empty($google_analytics_key)) $config_generated.="    google_analytics_key: '" . $google_analytics_key . "'\n";
$config_generated.=" \n";
$config_generated.="    piwik: ## Section \n";
$config_generated.=" \n";

$config_generated.="  settings: ## Section\n";
$config_generated.="\n";
if(!empty($pod_name)) $config_generated.="    pod_name: '" . $pod_name . "'\n";
if ($enable_registrations == 'on') $config_generated.="    enable_registrations: true \n";
if ($autofollow_on_join == 'on') $config_generated.="    autofollow_on_join: true \n";
if(!empty($autofollow_on_join_user)) $config_generated.="    autofollow_on_join_user: '" . $autofollow_on_join_user . "'\n";
$config_generated.=" \n";

$config_generated.="    invitations: ## Section\n";
$config_generated.=" \n";
if ($invitation_enable == 'on') $config_generated.="      open: true \n";
if(!empty($invitation_count)) $config_generated.="      count: '" . $invitation_count . "'\n";
$config_generated.=" \n";

if(!empty($paypal_hosted_button_id)) $config_generated.="    paypal_hosted_button_id: '" . $paypal_hosted_button_id . "'\n";
if(!empty($bitcoin_wallet_id)) $config_generated.="    bitcoin_wallet_id: '" . $bitcoin_wallet_id . "'\n";
$config_generated.=" \n";
$config_generated.="    community_spotlight: ## Section \n";
$config_generated.=" \n";

$config_generated.="  mail: ## Section\n";
$config_generated.=" \n";
if ($mail_enable == 'on') $config_generated.="    enable: true\n";
$config_generated.=" \n";
if(!empty($sender_address)) $config_generated.="    sender_address: '" . $sender_address . "'\n";
$config_generated.=" \n";
if(!empty($mail_method)) $config_generated.="    method: '" . $mail_method . "'\n";
$config_generated.=" \n";
$config_generated.="    smtp: ## Section\n";
$config_generated.=" \n";

if(!empty($smtp_host)) $config_generated.="      host: '" . $smtp_host . "'\n";
if(!empty($smtp_port)) $config_generated.="      port: " . $smtp_port . "\n";
if(!empty($smtp_authentication)) $config_generated.="      authentication: '" . $smtp_authentication . "'\n";
if(!empty($smtp_login)) $config_generated.="      username: '" . $smtp_login . "'\n";
if(!empty($smtp_pass)) $config_generated.="      password: '" . $smtp_pass . "'\n";

if ($mail_tls_auto == 'on') $config_generated.="      starttls_auto: true \n";
if(!empty($mail_helo)) $config_generated.="      domain: '" . $mail_helo . "'\n";

if(!empty($openssl_verify_mode)) $config_generated.="      openssl_verify_mode: '" . $openssl_verify_mode . "'\n";

$config_generated.="    sendmail: ## Section\n";
$config_generated.="      location: '/usr/sbin/sendmail'\n";

if(!empty($message_bus_api_key)) $config_generated.="    message_bus_api_key: '" . $message_bus_api_key . "'\n";
	
	
$config_generated.="  services: ## Section\n";
$config_generated.=" \n";
$config_generated.="    facebook: ## Section\n";
if ($facebook_enable == 'on') 
	{
	$config_generated.="      enable: true \n";
	$config_generated.="      app_id: '" . $facebook_key . "' \n";
	$config_generated.="      secret: '" . $facebook_secret . "'\n";
	}

$config_generated.=" \n";
$config_generated.="    twitter: ## Section\n";
if ($twitter_enable == 'on') 
	{
	$config_generated.="      enable: true \n";
	$config_generated.="      key: '" . $twitter_key . "' \n";
	$config_generated.="      secret: '" . $twitter_secret . "'\n";
	}

$config_generated.=" \n";
$config_generated.="    tumblr: ## Section\n";
if ($tumblr_enable == 'on') 
	{
	$config_generated.="      enable: true \n";
	$config_generated.="      key: '" . $tumblr_key . "' \n";
	$config_generated.="      secret: '" . $tumblr_secret . "'\n";
	}

$config_generated.=" \n";
$config_generated.="    wordpress: ## Section\n";
if ($wordpress_enable == 'on') 
	{
	$config_generated.="      enable: true \n";
	$config_generated.="      client_id: '" . $wordpress_key . "' \n";
	$config_generated.="      secret: '" . $wordpress_secret . "'\n";
	}

$config_generated.=" \n";
$config_generated.="  admins: ## Section\n";
$config_generated.=" \n";

if(!empty($admin_account)) $config_generated.="    account: '" . $admin_account . "'\n";
if(!empty($podmin_email)) $config_generated.="    podmin_email: '" . $podmin_email . "'\n";

$config_generated.=" \n";
$config_generated.="production: ## Section\n";
$config_generated.="  environment: ## Section\n";
$config_generated.=" \n";
$config_generated.="development: ## Section \n";
$config_generated.="  environment: ## Section\n";
$config_generated.=" \n";


// $config_generated and $config_syst are ok ? ready to go !

?>
