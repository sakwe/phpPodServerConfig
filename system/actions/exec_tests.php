<?php
$actionId = uniqid();
// last arg is bolean : true if you refresh the display (re-run the script from here) or false execute 
$this->podServerSystem->addTask("root","apt-get update",UPDATE_PACKAGES,$actionId,true);
$this->podServerSystem->addTask("root","apt-get -y upgrade",UPGRADE_PACKAGES,$actionId,true);
$this->podServerSystem->addTask(SYST_USER_FOR_DIASPORA,"cd ".DIRECTORY_DIASPORA,GO_TO_DIASPORA_DIRECTORY,$actionId,true);
$this->podServerSystem->addTask(SYST_USER_FOR_DIASPORA,"git pull",GIT_PULL,$actionId,false);
$this->podServerSystem->addTask(SYST_USER_FOR_DIASPORA,"cd ".DIRECTORY_DIASPORA,GO_TO_DIASPORA_DIRECTORY,$actionId,true);
$this->podServerSystem->addTask(SYST_USER_FOR_DIASPORA,"bundle",UPDATE_BUNDLE,$actionId,false);
$this->podServerSystem->addTask(SYST_USER_FOR_DIASPORA,"cd ".DIRECTORY_DIASPORA,GO_TO_DIASPORA_DIRECTORY,$actionId,true,false);
$this->podServerSystem->addTask(SYST_USER_FOR_DIASPORA,"RAILS_ENV=production bundle exec rake db:migrate",UPDATE_DATABASE,$actionId,false);
$this->podServerSystem->addTask(SYST_USER_FOR_DIASPORA,"cd ".DIRECTORY_DIASPORA,GO_TO_DIASPORA_DIRECTORY,$actionId,true);
$this->podServerSystem->addTask(SYST_USER_FOR_DIASPORA,"bundle exec rake assets:precompile",UPDATE_ASSETS,$actionId,false);

?>
