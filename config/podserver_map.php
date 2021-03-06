<?php

/*************************************************
This is the map entry for the global configuration
**************************************************

Usage for types : 
-----------------

text	 => array('NAME','GROUP','DEPEND','text','DEFAULT_VALUE','_NOT_USED_'),
pass	 => array('NAME','GROUP','DEPEND','pass','DEFAULT_VALUE','_NOT_USED_'),
checkbox => array('NAME','GROUP','DEPEND','checkbox','DEFAULT_VALUE','_NOT_USED_'),,
select	 => array('NAME','GROUP','DEPEND','select','DEFAULT_VALUE','POSSIBLE_VALUES'),
file	 => array('NAME','GROUP','DEPEND','file','SYST_USER','DIRECTORY_PATH'), => SYST_USER that have write access to the directory
system	 => array('NAME','GROUP','DEPEND','system','SYST_USER','ACTION'),	=> SYST_USER that runs the system commands for the action
title	 => array('NAME','GROUP','DEPEND','title','_NOT_USED_','_NOT_USED_'),   => fix the title in the file "languages/XX/lang.XX.php" for "$label_NAME" variable
html	 => array('NAME','GROUP','DEPEND','html','_NOT_USED_','DEFAULT'),


General tructure of the configuration datas in "$podserver_config_map[]" array : 
------------------------------------------------------------------------------

	1- NAME : name of the variable/item 
		you can add items in the configuration by adding rows with a "NAME" that DOESN'T EXISTS in this map (NAMEs are unique IDs in the software)

	2- GROUP : name of the group of datas (tabs) ex : general, network, system
		you can add a new group that will render a new tab with your items into it
		/!\ you have to order items grouped by 'GROUP' field in the array feed below

	3- DEPEND : put the name of the item from witch the accessibility depends
		the field will be disabled if check box not checked or selected value is "none" or text value is empty

	4- TYPE : the type of data renderer (text, pass, checkbox, select, title, file, system or html)
		you can add a type renderer by adding the correct "switch case" in the "ItemConfiguration->getHTML()" method in the "podserver_configuration.php" file

	5- DEFAULT_VALUE : will receive the correct value from the "config-podserver.php" file. Here you can put a default value for the item
	   SYST_USER     : system user for "file" or "system" types 

	6- POSSIBLE_VALUES : for select options items -> Put the possible values separated by coma (ex:'opt1,opt2,opt3')
	   DIRECTORY_PATH  : for file types, if a directory is given, the file into "uploads/" will be copied into when "apply" action is run
	   ACTION          : for system types, it's the name of the action that will be run (see more below)

Labels for items in the interface : 
-----------------------------------

	- will display "$label_ITEM_NAME" (name of the variable that must contain the text traduction) 
		if the variable $label_ITEM_NAME doesn't exists 
		if the variable $label_ITEM_NAME is empty in the language file
		if there is "?lang=debug" in the url that able to display all vairiable names for all labels, title and "define"s (CONST)

	- will display the text from $label_ITEM_NAME in the language file
	

Special items : 
---------------

	- "title" type	: will take the right title from the $label_ITEM_NAME from the language file

	- "system" type : render a button that run the action puts in the "possible values" field 
		you can add new actions by adding "system/actions/exec_YOURACTION.php"
		ex : 	array('sys_reboot','system','none','system','','reboot') 
			- will create a button named "sys_reboot" that call "system/actions/exec_reboot.php"
			- the label will be in the "$label_sys_reboot" variable in the language file

	- "file" type	: will upload the file named with the "name" field  to the "uploads/" directory
			  will copy the file to the directory path in "value" field (if given) on "apply" action
	NOT DONE -> will filter file extention with the "possible values" field. Types separated by coma (ex:'zip,gz,img')

*************************************************/

// get the gateway ip address for defaults
exec("ip route | grep default",$output,$status);
$pat[0]= '/default via /';
$pat[1]= '/ dev eth0/';
$remp[0]= '';
$remp[1]= '';
$gateway =  preg_replace($pat,$remp,$output[0]);

// get the domain name from current global configuration
global $domain_name;

$podserver_config_map = array
	(
	// networdk items to configure
	array('title_network','network','none','title','',''),
						// give local ip for default 
	array('local_ip','network','none','text',$_SERVER['SERVER_ADDR'],''),
						// give gateway ip address for default 
	array('gateway','network','none','text',$gateway,''),
	array('title_dyndns','network','none','title','',''),
	array('dyndns_method','network','none','select','','none,ovh,dyndns'),
	array('dyndns_login','network','dyndns_method','text','',''),
	array('dyndns_pass','network','dyndns_method','password','',''),
	array('title_ssl','network','none','title','',''),
	array('ssl_enable','network','none','checkbox','',''),
	array('ssl_cert','network','ssl_enable','file','root',DIRECTORY_SSL_CERTIFICATE.$domain_name.'/'),
	array('ssl_key','network','ssl_enable','file','root',DIRECTORY_SSL_CERTIFICATE.$domain_name.'/'),
	array('ssl_ca','network','ssl_enable','file','root',DIRECTORY_SSL_CERTIFICATE.$domain_name.'/'),


	// pod items to configure
	array('title_diasp','pod','none','title','',''),
	array('domain_name','pod','none','text','',''),
	array('pod_port','pod','none','text','3000',''),
	array('pod_name','pod','none','text','',''),
	array('title_database','pod','none','title','',''),
	array('db_host','pod','none','text','localhost',''),
	array('db_port','pod','none','text','3306',''),
	array('db_username','pod','none','text','',''),
	array('db_password','pod','none','password','',''),
	array('db_charset','pod','none','select','','utf8'),

	// general items to configure
	array('title_general','general','none','title','',''),
	array('jquery_enable','general','none','checkbox','',''),
	array('google_analytics_key','general','none','text','',''),
	array('enable_registrations','general','none','text','',''),
	array('autofollow_on_join','general','none','checkbox','',''),
	array('autofollow_on_join_user','general','autofollow_on_join','text','',''),
	array('invitation_enable','general','none','checkbox','',''),
	array('invitation_count','general','invitation_enable','text','',''),
	array('paypal_hosted_button_id','general','none','text','',''),
	array('bitcoin_wallet_id','general','none','text','',''),

	// email items to configure
	array('title_mail','mail','none','title','',''),
	array('mail_enable','mail','none','checkbox','',''),
	array('mail_method','mail','mail_enable','select','','sendmail,smtp,messagebus'),
	array('sender_address','mail','mail_enable','text','',''),
	array('smtp_host','mail','mail_enable','text','',''),
	array('smtp_port','mail','mail_enable','text','',''),
	array('smtp_authentication','mail','mail_enable','select','','none,plain,login,cram_md5'),
	array('smtp_login','mail','smtp_authentication','text','',''),
	array('smtp_pass','mail','smtp_authentication','password','',''),
	array('mail_tls_auto','mail','mail_enable','checkbox','',''),
	array('mail_helo','mail','mail_enable','text','',''),
	array('openssl_verify_mode','mail','mail_enable','select','','none,peer,client_once,fail_if_no_peer_cert'),
	array('message_bus_api_key','mail','mail_enable','text','',''),

	// service items to configure
	array('title_services','services','none','title','',''),
	array('facebook_enable','services','none','checkbox','',''),
	array('facebook_key','services','facebook_enable','text','',''),
	array('facebook_secret','services','facebook_enable','text','',''),
	array('twitter_enable','services','none','checkbox','',''),
	array('twitter_key','services','twitter_enable','text','',''),
	array('twitter_secret','services','twitter_enable','text','',''),
	array('tumblr_enable','services','none','checkbox','',''),
	array('tumblr_key','services','tumblr_enable','text','',''),
	array('tumblr_secret','services','tumblr_enable','text','',''),
	array('wordpress_enable','services','none','checkbox','',''),
	array('wordpress_key','services','wordpress_enable','text','',''),
	array('wordpress_secret','services','wordpress_enable','text','',''),

	// podmin items to configure
	array('title_diasp_admin','podmin','none','title','',''),
	array('admin_account','podmin','none','text','',''),
	array('podmin_email','podmin','none','text','',''),

	// admin items for system operations

	//*****************************************************************************************
	// password change management
	// DO NOT REMOVE IT IF you want to be able to change system passaword
	// "podServerDispatcher" need to _POST "f_pass_current" , "f_pass_new" and "f_pass_conf" items for "passchange"
	array('title_password_change','system','none','title','',''),
	array('f_pass_current','system','none','password','',''),
	array('f_pass_new','system','none','password','',''),
	array('f_pass_conf','system','none','password','',''),
	array('sys_passchange','system','f_pass_new','system','','passchange'),
	//*****************************************************************************************

	array('title_system_manage','system','none','title','',''),

	//-------------------------------------------------------------
	// apply action works seemed into PodServer code so you don't need the "system/actions/exec_apply.php"
	// unless you need to change the default "apply" system that works with "config/makers/make-config-*.php" files 
	array('sys_apply','system','none','system','','apply'),
	//-------------------------------------------------------------

	// these are system action items that run system/actions/exec_ACTIONAME.php scripts
	array('sys_restart','system','none','system','root','restart'),
	array('sys_reboot','system','none','system','root','reboot'),
	array('sys_shutdown','system','none','system','root','shutdown'),
	array('sys_update','system','none','system','root','update'),
	array('sys_tests','system','none','system','root','tests'),

	// these are PodServer software config items
	// set the language 
	array('DEF_PODSERVER_LANGUAGE','install','none','select',PODSERVER_LANGUAGE,$_SESSION['supported_languages']),
	// set your diaspora system user
	array('DEF_SYST_USER_FOR_DIASPORA','install','none','text',SYST_USER_FOR_DIASPORA,''),
	// set your diaspora directory
	array('DEF_DIRECTORY_DIASPORA','install','none','text',DIRECTORY_DIASPORA,''),
	// set your ssl directory
	array('DEF_DIRECTORY_SSL_CERTIFICATE','install','none','text',DIRECTORY_SSL_CERTIFICATE,''),
	// set the path to the network interfaces file
	array('DEF_FILE_NETWORK_INTERFACES','install','none','text',FILE_NETWORK_INTERFACES,''),
	// set the path to the vhost file for the diaspora virtual server in apache
	array('DEF_FILE_APACHE_VHOST','install','none','text',FILE_APACHE_VHOST,''),
	// set the path to the dyndns script 
	array('DEF_FILE_DYNDNS','install','none','text',FILE_DYNDNS,'')

	);

?>

