<?php

/**
 * Include this file to include all files and classes for the phpPodServer
 */


// need a session for some datas
session_start();
//session_destroy();

// get the system configuration (directories, files, command)
include($_SERVER['DOCUMENT_ROOT'].'/config.php');

// get the language for the interface
include($_SERVER['DOCUMENT_ROOT'].'/languages/podserver_language.php');

// get the current PodServer global configuration
include($_SERVER['DOCUMENT_ROOT'].'/config/podserver_config.php');

// include the PodServer class
include($_SERVER['DOCUMENT_ROOT'].'/system/podserver.class.php');


?>
