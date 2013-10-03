<?php
// files path
$config_done = $_SERVER['DOCUMENT_ROOT'].'/config/files/config-diaspora.conf';
$config_syst = CONFIG_DIRECTORY_DIASPORA.'/config/diaspora.yml';

// ouverture en écriture du fichier inc.config.php
if(!$file = @fopen('base-config/diasp.conf', 'w')) {
	// si erreur on revient vers l'édition
	header("Location: config.php?msg=11");
	exit;
}
fwrite($file, "configuration: ## Section\n");
fwrite($file, " \n");
fwrite($file, "  environment: ## Section\n");
fwrite($file, " \n");
fwrite($file, "    url: '" . (($ssl_enable == 'on') ? "https" : "http") . "://" . $domain_name . "/'\n");
fwrite($file, "    certificate_authorities: '/etc/ssl/certs/ca-certificates.crt' \n");
if ($ssl_enable == 'on') fwrite($file, "    require_ssl: true \n");
fwrite($file, " \n");
fwrite($file, "    sidekiq: ## Section\n");
fwrite($file, " \n");
fwrite($file, "    s3: ## Section \n");
fwrite($file, " \n");
fwrite($file, "    assets: ## Section \n");
fwrite($file, " \n");
fwrite($file, "  server: ## Section\n");
fwrite($file, "    port: " . $pod_port . " \n");
fwrite($file, "    rails_environment: 'production' \n");
fwrite($file, " \n");
fwrite($file, "  privacy: ## Section\n");
if ($jquery == 'on') fwrite($file, "    jquery_cdn: true \n");
if(!empty($google_analytics_key)) fwrite($file, "    google_analytics_key: '" . $google_analytics_key . "'\n");
fwrite($file, " \n");
fwrite($file, "    piwik: ## Section \n");
fwrite($file, " \n");
fwrite($file, "  settings: ## Section\n");
fwrite($file, "\n");
if(!empty($pod_name)) fwrite($file, "    pod_name: '" . $pod_name . "'\n");
if ($enable_registrations == 'on') fwrite($file, "    enable_registrations: true \n");
if ($autofollow_on_join == 'on') fwrite($file, "    autofollow_on_join: true \n");
if(!empty($autofollow_on_join_user)) fwrite($file, "    autofollow_on_join_user: '" . $autofollow_on_join_user . "'\n");
fwrite($file, " \n");
fwrite($file, "    invitations: ## Section\n");
fwrite($file, " \n");
if ($invitation_enable == 'on') fwrite($file, "      open: true \n");
if(!empty($invitation_count)) fwrite($file, "      count: '" . $invitation_count . "'\n");
fwrite($file, " \n");
if(!empty($paypal_hosted_button_id)) fwrite($file, "    paypal_hosted_button_id: '" . $paypal_hosted_button_id . "'\n");
if(!empty($bitcoin_wallet_id)) fwrite($file, "    bitcoin_wallet_id: '" . $bitcoin_wallet_id . "'\n");
fwrite($file, " \n");
fwrite($file, "    community_spotlight: ## Section \n");
fwrite($file, " \n");
fwrite($file, "  mail: ## Section\n");
fwrite($file, " \n");
if ($mail_enable == 'on') fwrite($file, "    enable: true\n");
fwrite($file, " \n");
if(!empty($sender_address)) fwrite($file, "    sender_address: '" . $sender_address . "'\n");
fwrite($file, " \n");
if(!empty($mail_method)) fwrite($file, "    method: '" . $mail_method . "'\n");
fwrite($file, " \n");
fwrite($file, "    smtp: ## Section\n");
fwrite($file, " \n");

if(!empty($smtp_host)) fwrite($file, "      host: '" . $smtp_host . "'\n");
if(!empty($smtp_port)) fwrite($file, "      port: " . $smtp_port . "\n");
if(!empty($smtp_authentication)) fwrite($file, "      authentication: '" . $smtp_authentication . "'\n");
if(!empty($smtp_login)) fwrite($file, "      username: '" . $smtp_login . "'\n");
if(!empty($smtp_pass)) fwrite($file, "      password: '" . $smtp_pass . "'\n");
if ($mail_tls_auto == 'on') fwrite($file, "      starttls_auto: true \n");
if(!empty($mail_helo)) fwrite($file, "      domain: '" . $mail_helo . "'\n");
if(!empty($openssl_verify_mode)) fwrite($file, "      openssl_verify_mode: '" . $openssl_verify_mode . "'\n");

fwrite($file, "    sendmail: ## Section\n");
fwrite($file, "      location: '/usr/sbin/sendmail'\n");

if(!empty($message_bus_api_key)) fwrite($file, "    message_bus_api_key: '" . $message_bus_api_key . "'\n");
	
	
fwrite($file, "  services: ## Section\n");
fwrite($file, " \n");
fwrite($file, "    facebook: ## Section\n");
if ($facebook_enable == 'on') 
	{
	fwrite($file, "      enable: true \n");
	fwrite($file, "      app_id: '" . $facebook_key . "' \n");
	fwrite($file, "      secret: '" . $facebook_secret . "'\n");
	}
fwrite($file, " \n");
fwrite($file, "    twitter: ## Section\n");
if ($twitter_enable == 'on') 
	{
	fwrite($file, "      enable: true \n");
	fwrite($file, "      key: '" . $twitter_key . "' \n");
	fwrite($file, "      secret: '" . $twitter_secret . "'\n");
	}
fwrite($file, " \n");
fwrite($file, "    tumblr: ## Section\n");
if ($tumblr_enable == 'on') 
	{
	fwrite($file, "      enable: true \n");
	fwrite($file, "      key: '" . $tumblr_key . "' \n");
	fwrite($file, "      secret: '" . $tumblr_secret . "'\n");
	}
fwrite($file, " \n");
fwrite($file, "    wordpress: ## Section\n");
if ($wordpress_enable == 'on') 
	{
	fwrite($file, "      enable: true \n");
	fwrite($file, "      client_id: '" . $wordpress_key . "' \n");
	fwrite($file, "      secret: '" . $wordpress_secret . "'\n");
	}
fwrite($file, " \n");
fwrite($file, "  admins: ## Section\n");
fwrite($file, " \n");

if(!empty($admin_account)) fwrite($file, "    account: '" . $admin_account . "'\n");
if(!empty($podmin_email)) fwrite($file, "    podmin_email: '" . $podmin_email . "'\n");

fwrite($file, " \n");
fwrite($file, "production: ## Section\n");
fwrite($file, "  environment: ## Section\n");
fwrite($file, " \n");
fwrite($file, "development: ## Section \n");
fwrite($file, "  environment: ## Section\n");
fwrite($file, " \n");
fclose($file);


?>
