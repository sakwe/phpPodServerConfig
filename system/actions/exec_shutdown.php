<?php
$actionId = uniqid();
$this->podServerSystem->addTask("root","shutdown -h now",SHUTDOWN_SERVER,$actionId);

?>
