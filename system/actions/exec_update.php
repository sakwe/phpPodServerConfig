<?php

$this->podServerSystem->addTask("root","apt-get update",UPDATE_PACKAGES);
$this->podServerSystem->addTask("root","apt-get -y upgrade",UPGRADE_PACKAGES);
$this->podServerSystem->addTask(SYST_USER_FOR_DIASPORA,"cd ".DIRECTORY_DIASPORA,GO_TO_DIASPORA_DIRECTORY);
$this->podServerSystem->addTask(SYST_USER_FOR_DIASPORA,"git pull",GIT_PULL);
$this->podServerSystem->addTask(SYST_USER_FOR_DIASPORA,"bundle",UPDATE_BUNDLE);
$this->podServerSystem->addTask(SYST_USER_FOR_DIASPORA,"RAILS_ENV=production bundle exec rake db:migrate",UPDATE_DATABASE);
$this->podServerSystem->addTask(SYST_USER_FOR_DIASPORA,"bundle exec rake assets:precompile",UPDATE_ASSETS);

?>
