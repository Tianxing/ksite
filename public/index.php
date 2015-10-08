<?php
define("APP_NAME", 'ksite'); 
define("YAF_PATH", realpath(dirname(__FILE__) . '/../')); 
define("APP_PATH", YAF_PATH . '/application/' . APP_NAME);
define("LOG_PATH", realpath(YAF_PATH . '/../log/' . APP_NAME));

$app  = new Yaf_Application(YAF_PATH . "/conf/application.ini");
$app->bootstrap()->run();
