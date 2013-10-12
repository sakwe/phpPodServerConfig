<?php
$actionId = uniqid();
$this->podServerSystem->addTask("root","/etc/init.d/networking restart",RESTART_NETWORK,$actionId);
$this->podServerSystem->addTask("root","/etc/init.d/apache2 reload",RESTART_APACHE,$actionId);
$this->podServerSystem->addTask("root","/etc/init.d/redis-server restart",RESTART_REDIS,$actionId);
$this->podServerSystem->addTask("root","/etc/init.d/diaspora restart",RESTART_DIASPORA,$actionId);

?>
