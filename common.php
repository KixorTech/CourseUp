<?php
/*
This file is part of the CourseUp project.
http://courseup.org

(c) Micah Taylor
micah@kixortech.com

See http://courseup.org for license information.
*/



require_once("helpers.php");
require_once("session.php");
require_once("https.php");
require_once("Config.php");
//require_once("basicAuth.php");
//require_once('db.php');

require_once('spyc.php');
$configPath = getFileRoot().'/../config.yaml';
$config = spyc_load_file($configPath);
Config::getInstance()->loadSettings($config);

$online = checkOnline();
if($online)
	$session = Session::init();

require_once('quiz.php');
require_once('rubric.php');


?>

