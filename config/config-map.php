<?php

/* 

This is the map entry for the global configuration
--------------------------------------------------

Structure of the configuration datas in "$podserver_config_map" array : 

	1- name : name of the variable/item 
		you can add items in the configuration by adding an ITEM_NAME that DOESN'T EXISTS in this map

	2- group : name of the group of datas (tabs)
		you can add a new group that will render a new tab with your items into it
		/!\ you have to order items by groups in this map feeding

	3- depend : put the name of the item from witch the accessibility depends
		disabled if check box not checked or selected value is none

	4- type : the type of data renderer (text, checkbox, pass, file, ...)
		you can add a type renderer by adding the correct "switch case" in the "ItemConfiguration->getHTML()" method in the "podserver_configuration.php" file

	5- value : will receive the correct value from the config file. Here you can put a default value for the item

	6- possible values : for select options items -> Put the possible values separated by coma (ex:'opt1,opt2,opt3')


Labels for items in the interface : 

	- will display "label_ITEM_NAME" if the variable $label_ITEM_NAME doesn't exists in the language file

	- will display the label from "$label_ITEM_NAME" in the language file

Special items : 

	- title type : will take the right title from the $label_ITEM_NAME from the language file

	- "system" type : render a button that run the action puts in the "possible values" field 
		you can add new actions by adding "system/actions/exec_YOURACTION.php"
		ex : 	array('sys_reboot','system','none','system','','reboot') 
			- will create a button named "sys_reboot" that call "system/actions/exec_reboot.php"
			- the label will be in the "$label_sys_reboot" variable in the language file

*/

// get the gateway ip address for defaults
exec("ip route | grep default",$output,$status);
$pat[0]= '/default via /';
$pat[1]= '/ dev eth0/';
$remp[0]= '';
$remp[1]= '';
$gateway =  preg_replace($pat,$remp,$output[0]);


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

	// pod items to configure
	array('title_diasp','pod','none','title','',''),
	array('domain_name','pod','none','text','',''),
	array('pod_port','pod','none','text','',''),
	array('pod_name','pod','none','text','',''),
	array('title_ssl','pod','none','title','',''),
	array('ssl_enable','pod','none','checkbox','',''),
	array('ssl_cert','pod','ssl_enable','file','',''),
	array('ssl_key','pod','ssl_enable','file','',''),
	array('ssl_ca','pod','ssl_enable','file','',''),

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

	// admin items to configure
	array('title_diasp_admin','administration','none','title','',''),
	array('admin_account','administration','none','text','',''),
	array('podmin_email','administration','none','text','',''),

	// admin items for system operations

	//-------------------------------------------------------------
	// password change management. use it if you want to be able to change system passaword
	// you need to keep the "system/actions/exec_passchange.php" file in the right place
	array('title_password_change','system','none','title','',''),
	array('f_pass_new','system','none','text','',''),
	array('f_pass_conf','system','none','text','',''),
	array('sys_passchange','system','f_pass_new','system','','passchange'),
	//--------------------------------------------------------------

	array('title_system_manage','system','none','title','',''),

	//-------------------------------------------------------------
	// apply action works seemed into PodServer code so you don't need the "system/actions/exec_apply.php"
	// unless you need to change the default "apply" system tha works with makers 
	array('sys_apply','system','none','system','','apply'),
	//-------------------------------------------------------------

	// these are system actions that run system/actions/exec_ACTIONAME.php scripts
	array('sys_restart','system','none','system','','restart'),
	array('sys_reboot','system','none','system','','reboot'),
	array('sys_shutdown','system','none','system','','shutdown'),
	array('sys_update','system','none','system','','update')
	);

?>

