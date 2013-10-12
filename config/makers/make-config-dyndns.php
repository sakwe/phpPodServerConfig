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
#! /bin/bash 

# OVH - DynHost
#
# Permet de mettre à jour le champ DYNHOST
# pour votre nom de domaine.
# Utilise l\'adresse de l\'interface ppp0 de 
# votre système Linux.

# La mise à jour ne se fait que si l\'adresse IP
# a effectivement changé.
# Fichier de log: dynhost.log
cd /var/lib/bind/dyndnsOVH/

#IP=$(wget -q -O - http://automation.whatismyip.com/n09230945.asp)

IP=$(curl -s icanhazip.com)

#IP="192.168.0.1" (for tests)
OPTIONS="-a "$IP
OLDIP=`cat ./old.ip`
echo ---------------------------------- >> ./dynhost.log
echo `date` >> ./dynhost.log 
echo Démarrage de DynHost >> ./dynhost.log

if [ "$IP" ]; then
	if [ "$OLDIP" != "$IP" ]; then
		echo -n "Ancienne IP: " >> ./dynhost.log
		echo $OLDIP >> ./dynhost.log
		echo -n "Nouvelle IP: " >> ./dynhost.log
		echo $IP >> ./dynhost.log
		echo "Mise à jour!" >> ./dynhost.log
		if [ "$OPTIONS" =  "" ]; then 
			OPTIONS="-a $IP" 
		fi
		python ipcheck.py $OPTIONS "'.$dyndns_login.'" "'.dyndns_pass.'" "'.$domain_name.'" >> ./dynhost.log
		echo "IP mise a jour chez OVH : $IP"
		echo -n "$IP" > ./old.ip				
 	else
       		echo IP Identique! Pas de mise à jour. >> ./dynhost.log
 	fi
 else
 	echo Panique à bord: Aucune IP Disponible!! >> ./dynhost.log
 fi
';



// $config_generated and $config_syst are ok ? ready to go !

?>
