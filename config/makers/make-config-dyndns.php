<?php
// files path
global $config_syst;
$config_syst = FILE_DYNDNS;
$config_user_do = 'root';
//---------------------------------------------------------------------

// get the PodServer global configuration
include($_SERVER['DOCUMENT_ROOT'].'/config/config-podserver.php');
//----------------------------------------------------------------


// clean the config variable
global $config_generated;
$config_generated = "";
//-----------------------------

$config_generated.= '
#! /bin/bash \n
\n
# OVH - DynHost\n
#\n
# Permet de mettre à jour le champ DYNHOST\n
# pour votre nom de domaine.\n
# Utilise l\'adresse de l\'interface ppp0 de \n
# votre système Linux.\n

# La mise à jour ne se fait que si l\'adresse IP\n
# a effectivement changé.\n
# Fichier de log: dynhost.log\n
cd /var/lib/bind/dyndnsOVH/\n
\n
#IP=$(wget -q -O - http://automation.whatismyip.com/n09230945.asp)\n

IP=$(curl -s icanhazip.com)\n
\n
#IP="192.168.0.1" (for tests)\n
OPTIONS="-a "$IP\n
OLDIP=`cat ./old.ip`\n
echo ---------------------------------- >> ./dynhost.log\n
echo `date` >> ./dynhost.log \n
echo Démarrage de DynHost >> ./dynhost.log\n
\n
if [ "$IP" ]; then\n
	if [ "$OLDIP" != "$IP" ]; then\n
		echo -n "Ancienne IP: " >> ./dynhost.log\n
		echo $OLDIP >> ./dynhost.log\n
		echo -n "Nouvelle IP: " >> ./dynhost.log\n
		echo $IP >> ./dynhost.log\n
		echo "Mise à jour!" >> ./dynhost.log\n
		if [ "$OPTIONS" =  "" ]; then \n
			OPTIONS="-a $IP"\n 
		fi\n
		python ipcheck.py $OPTIONS "'.$dyndns_login.'" "'.dyndns_pass.'" "'.$domain_name.'" >> ./dynhost.log\n
		echo "IP mise a jour chez OVH : $IP"\n
		echo -n "$IP" > ./old.ip\n				
 	else\n
       		echo IP Identique! Pas de mise à jour. >> ./dynhost.log\n
 	fi\n
 else\n
 	echo Panique à bord: Aucune IP Disponible!! >> ./dynhost.log\n
 fi\n
';



// $config_generated and $config_syst are ok ? ready to go !

?>
