
=== phpPodServerConfig ===

The project is currently unusable.
------------------------------

Version: DEVELOPMENT

Authors: Sakwe
Contributors: Flaburgan
Tags: diaspora, pod, configuration
Tested with Apache 2.2.22 Php5 on Debian Wheezy Stable
Require: apache, openssl, openssh 

Tool to easily configure a server for Diaspora* Pod hosting
-----------------------------------------------------------

=== Description ===

The first aim of this project is to gather principal configuration items of different softwares and services that are needed to run a Diaspora* Pod.
It render a very basic "all-in-one" interface that enable to catch, record and apply the configuration to the system. 
It configure the Diaspora* application, the apache Virtual Host for the Pod, the network interface with static IP of your choice, the DynDns/DynOVH script that refresh your dynamic IP, the SSL files to enable HTTPS on your Pod.

=== Additional Project ===

This project comes with an optional Virtual Machine pre-installed and configured for a Diaspora* Pod hosting.
With these tools, someone who never knows how to install a web server can host a little Diaspora* Pod.
The console welcome message gives you the url to access the "phpPodServerConfig" at boot up. Configure what you need at first boot, apply and run your Pod*.

	Default logins to the Virtual Machine:
	- "root" password -> "diaspora"
	- "diaspora" password -> "diaspora" (default user member of "sudoers")

Note : I'll try to give a download link soon for the VM image


=== Installation ===

Just copy the php project content in the directory of your choice and ensure it's directory root for an apache Virtual Host.
If you have a fresh apache server installed and have no idea, just open the file "/etc/apache2/sites-available/default" and change the "DocumentRoot" value to your directory.

	CURRENTLY : 
	- You have to ensure www-data user (the one that runs apache) have write access at least to "config.php", "system/tasks.log" , "config/config-podserver.php" and the "config/files/" directory
	- You have to open "config.php" and type the correct system user for SYST_USER_FOR_DIASPORA that will log in to this software


=== Use ===

- Open a web browser and enter the right url (http://localhost would be ok if you are on the machine) 
- Log with the system password for the SYST_USER_FOR_DIASPORA
- Check your configuration
- Record the configuration
- Apply to the system
- Restart services
- Run the Pod ;-)

=== Development ===

	IN PROGRESS : 
	- tests for configuration apply
	- management of the tasks queue in sessions
	- restart network and apache from apache
	- use the Diaspora* start|stop|status script
	- cool global status displaying

	TODO LATER : 
	- SSL CERT : maybe create the key and cert file from 2 textareas to easily copy/paste from StartSsl website when create the certificate.
	- Backups  : create an option/action that configure a cron task that run a backup script
	- Installs : 
		- automatic installation for dependances
		- automatic creation of system users
		- automatic installation for Diaspora*


=== Structure ===

- The whole configuration interface is rendered with tabs that contains items. 
- Items are text input, check box, select list, passwords, titles or buttons.
- All items of the global configuration are built from : 
	- "confi-map.php" : description of items, you can read more details in this IMPORTANT file
	- "config-podserver.php" : values of items, auto-generated file by "PodServerConfiguration"
	- "languages/XX/lang.XX.php" : labels and titles of items, there you can make all translations you need (YOUR_URL/?lang=debug for "language debugging")
- The "PodServer" class (podserver.php) is the software's main object
	- it use the "PodServerSystem" class to autenticate trough SSH and run system commands (tasks)
	- it use the "PodServerConfiguration" class to load, render, record and apply the configuration
	- it use the "PodServerDispatcher" class to correctly dispatch "actions" on PodServer
- The system configuration files are generated by "config/makers/make-config-*.php" files where : 
	- "$config_syst" is the conf. file path in your system
	- "$config_user_do" is the user that copy the configuration system file (for "podServerSystem" copy task)
	- "$config_generated" is the content of the conf. file to write to the system
- The actions are described and ran from "system/actions/exec_*.php"
	- note that "login", "logout", "record" and "apply" actions are seemed into "phpPodServerConfig" code and don't have an "exec_*.php" file
	- an action file contains "podServerSystem->addTask(...)" entries
	- an action is ran by a "system item button" described in the "config/config-map.php"
- The main interface contains a monitor frame that execute tasks from the sessions tasks queue, one by one.
	- the monitor main script is "system/system-monitor.php"
	- the monitoring is done by a javascript "js/monitor.php" that check "system/system-current-task.php" and run "system/system-exec.php" if needed 
	- "system/system-exec.php" run a task or ask a login for a task or clean the queue if no task left in the queue.


=== Personal notes ===

	- auto login : /etc/inittab (ligne : 1:2345:respawn:/sbin/getty 38400 --autologin diaspora tty1)
	- console home screen : /usr/local/bin/dynmotd
