<?php

define("TITLE_PODSERVER","PodServer");

define("BIG_TITLE_POD_CONFIGURATION","Configuration et lancement rapide du Pod");

define("LABEL_SYS_USER","Utilisateur système");

define("LABEL_PASSWORD","Mot de passe");

define("BUTTON_LOGIN","Connexion");

define("BUTTON_LOGOUT","Déconnecter");

define("ERROR_SSH_LOGIN_TRY_AGAIN","Mot de pass incorrect. Essayez encore.");

define("ENTER_USER_AND_PASSOWRD","Saisissez votre nom d\"utilisateur et votre mot de passe");

define("ENTER_PASSWORD_FOR_USER","Saisissez le mot de passe pour l\"utilisateur");

define("THIS_TASK_NEED_AUTENTICATION","Cette tâche nécessite une authentification");

define("DIALOG_PASS_CHANGE","Changement de mot de passe pour l\"utilisateur \"".$_SESSION["current_sysuser"]."\"");

define("ERROR_PASSWORD_CONFIRMATION_NOT_THE_SAME","Le mot de passe fourni et la confirmation ne sont pas identiques. Veuilliez reconfirmer.");

define("ERROR_PASSWORD_MUST_HAVE_8_LETTERS","Le mot de passe doit contenir au moins 8 caractères.");

define("PASSWORD_CHANGED","Mot de passe modifié");

define("ENTER_A_SYSTEM_COMMAND","Entrez une commande système");

define("CLEAR_TASKS_QUEUE_HISTORY","Vider la queue");

define("SHOW_HIDE_TERMINAL","Montrer/cacher le terminal PodServer");

define("BUTTON_RECORD","Enregistrer");

define("CAN_APPLY_CONFIGURATION_TO_SYSTEM","La configuration globale a été sauvegardée. Vous pouvez maintenant appliquer cette configuration au système.");

define("CONFIGURATION_RECORDED","Configuration enregistrée");

define("ACTION_TO_DO_CATCH_FIELD","Vous devez saisir le champ");

define("ACTION_TO_DO_CHECK_FIELD","Vous devez activer le champ");

define("ACTION_TO_DO_SELECT_FIELD","Vous devez sélectionner le champ");

define("TO_ACCESS_THIS_FIELD","pour accéder à ce champ");

define("YES","Oui");

define("NO","Non");

define("CANCEL","Annuler");

define("CLOSE","Fermer");

define("INFO","Information");

define("SUCCESS","Réussi");

define("WARNING","Attention");

define("ERROR","Erreur");

define("QUESTION","Question");

define("PRESENT","Présent");

define("NONE","Aucun");

define("DELETE","Supprimer");

define("UPLOAD_FILE_ERROR","Erreur lors de l'envoi de fichier");

define("FILE_GENERATED_BY","Fichier généré par");

define("SYSTEM_TASK_IN_QUEUE","Tâche dans la file d'attente");

define("TASK_COPY_FILE_CONFIG","Copie d'un fichier de configuration système");

define("TASK_COPY_FILE_ITEM","Copie d'un fichier système envoyé");

define("TASK_FROM_PROMPT","Tâche lancée via le terminal PodServer");

define("ERROR_SSHLOGIN_INTO_SSHEXEC","Erreur de login ssh lors de l'exécution de la commande");

define("ERROR_CAN_NOT_OPEN_CONFIG_PODSERVER_PHP","Impossible d'ouvrir le fichier \"config/config-podserver.php\"");

define("NO_METHOD_TO_RENDER_THIS_ITEM","Ce type de champ n'est pas pris en charge");

// Labels for tabs headers
$label_tab_network	= "Réseau";
$label_tab_pod 		= "Pod";
$label_tab_general	= "Général";
$label_tab_mail 	= "Mail";
$label_tab_services 	= "Services";
$label_tab_podmin	= "Podmin";
$label_tab_system 	= "Système";
$label_tab_install 	= "Install";

// Labels and titles for network configuration
$label_title_network	= "Configuration réseau";
$label_local_ip		= "Adresse ip locale";
$title_local_ip		= "Vous devez rediriger les port HTTP et HTTPS de votre routeur vers cette adresse";
$label_gateway		= "Adresse de la passerelle";
$label_title_dyndns	= "Configuration du nom de domaine dynamique";
$label_dyndns_method	= "Fournisseur DynDNS";
$label_dyndns_login	= "Login DynDNS";
$label_dyndns_pass	= "Pass DynDNS";
$label_title_ssl	= "Configuration SSL";
$label_ssl_enable	= "Activer SSL (conseillé)";
$label_ssl_cert		= "Certificat SSL (.cert ou .crt)";
$label_ssl_key		= "Clé SSL (.key)";
$label_ssl_ca		= "Certificat d'autorité (.ca)";

// Labels and titles for pod configuration
$label_title_diasp	= "Indentification de votre Pod Diaspora*";
$label_domain_name	= "Nom de domaine";
$label_pod_port		= "Port utilisé par Rails";
$label_pod_name		= "Nom du Pod";
$label_title_database	= "Base de données de votre Pod (MySql)";
$label_db_host		= "Hostname du serveur";
$label_db_port		= "Port du serveur";
$label_db_username	= "Login pour la base de données";
$label_db_password	= "Mot de passe";
$label_db_charset	= "Encodage des caractères";

// Labels and titles for general configuration
$label_title_general		= "Configuration générale de votre Pod";
$label_jquery_enable		= "Utiliser jquery via Google (recommandé)";
$label_google_analytics_key	= "Votre clé Google analytics";
$title_google_analytics_key	= "Cette option vous permet de suivre vos statistiques de visites via Google analytics";
$label_enable_registrations	= "Autoriser l'inscription sur le Pod";
$label_autofollow_on_join	= "Suivre automatiquement une personne à l'inscription";
$label_autofollow_on_join_user	= "Personne à suivre automatiquement";
$label_invitation_enable	= "Autoriser les invitations";
$label_invitation_count		= "Nombre maximum d'invitation par utilisateur";
$label_paypal_hosted_button_id	= "Identifiant de votre bouton de don Paypal";
$title_paypal_hosted_button_id	= "Cette option vous permet d'obtenir un bouton de don Paypal à votre nom sur votre Pod";
$label_bitcoin_wallet_id	= "Identifiant de votre bouton de don Bitcoin";
$title_bitcoin_wallet_id	= "Cette option vous permet d'obtenir un bouton de don Bitcoin à votre nom sur votre Pod";

// Labels and titles for mail configuration
$label_title_mail		= "Configuration des paramètres mail";
$label_mail_enable		= "Activer la fonctionnalité mail";
$label_mail_method		= "Méthode d'envoi de courrier";
$label_sender_address		= "Adresse de l'expéditeur";
$label_smtp_host		= "Host SMTP";
$label_smtp_port		= "Port SMTP";
$label_smtp_authentication	= "Méthode d'autentification SMTP";
$label_smtp_login		= "Identifiant";
$label_smtp_pass		= "Mot de passe";
$label_mail_tls_auto		= "TLS automatique";
$label_mail_helo		= "Host pour la commande HELO";
$label_openssl_verify_mode	= "Mode de négociation openSSL";
$label_message_bus_api_key	= "API Key (si méthode message bus)";

// Labels and titles for services configuration
$label_title_services		= "Configurer les services connectés à votre Pod";
$label_facebook_enable		= "Activer le partage sur Facebook";
$label_facebook_key		= "Clé";
$label_facebook_secret		= "Secret";
$label_twitter_enable		= "Activer le partage sur Twitter";
$label_twitter_key		= "Clé";
$label_twitter_secret		= "Secret";
$label_tumblr_enable		= "Activer le partage sur Tumblr";
$label_tumblr_key		= "Clé";
$label_tumblr_secret		= "Secret";
$label_wordpress_enable		= "Activer le partage sur Wordpress";
$label_wordpress_key		= "Clé";
$label_wordpress_secret		= "Secret";

// Labels and titles for administration configuration
$label_title_diasp_admin	= "Paramètres du Podmin (administrateur du Pod)";
$label_admin_account		= "Utilisateur Podmin (identifiant court)";
$label_podmin_email		= "Adresse mail du Podmin";

// Labels and titles for system actions
$label_title_password_change 	= "Mot de passe l'utilisateur \"" . $_SESSION["current_sysuser"]."\"";
$label_f_pass_current		= "Mot de passe actuel";
$label_f_pass_new 		= "Nouveau mot de passe";
$label_f_pass_conf 		= "Confirmer le mot de passe";
$label_sys_passchange 		= "Modifier le mot de passe";
$help_sys_passchange 		= "Cette action va modifier le mot de passe système de l'utilisateur connecté.";
$label_title_system_manage 	= "Outils système";
$label_sys_apply 		= "Appliquer la configuration au système";
$help_sys_apply 		= "Cette action va générer les fichiers de configuration système et les copier aux emplacements nécessaires.";
$label_sys_restart		= "Redémarrer les services appropriés";
$help_sys_restart		= "Cette action va redémarrer tous les services utilisés par le Pod. Le réseau et Apache vont entre autre être relancés.";
$label_sys_reboot 		= "Redémarrer le serveur";
$help_sys_reboot 		= "Cette action va redémarrer complètement la machine. Tous les services devraient ensuite être à nouveau opérationnels.";
$label_sys_shutdown 		= "Éteindre le serveur";
$help_sys_shutdown 		= "Cette action éteind complètement le serveur. Vous devez ensuite le redémarrer manuellement. Cette interface ne sera plus accessibles.";
$label_sys_update 		= "Mettre à jour le système";
$help_sys_update 		= "Cette action va appliquer les mises à jour système. Les sources Diaspora* seront ensuite synchronisées et recompilées.";


// Labels for this software install configuration items
$label_DEF_PODSERVER_LANGUAGE		= "Langue utilisée pour l\"interface\"";
$label_DEF_SYST_USER_FOR_DIASPORA	= "Utilisateur système pour Diaspora*";
$label_DEF_DIRECTORY_DIASPORA		= "Répertoire d'installation de Diaspora*";
$label_DEF_DIRECTORY_SSL_CERTIFICATE	= "Répertoire système pour les certificats SSL";
$label_DEF_FILE_NETWORK_INTERFACES	= "Fichier système de configuration réseau";
$label_DEF_FILE_APACHE_VHOST		= "Fichier de configuration apache (VirtualHost)";
$label_DEF_FILE_DYNDNS			= "Script de mise à jour DNS dynamique"

?>
