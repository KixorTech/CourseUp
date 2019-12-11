<?php
/*
This file is part of the CourseUp project.
http://courseup.org

(c) Micah Taylor
micah@kixortech.com

See http://courseup.org for license information.
*/


require_once('common.php');

class Config
{
	private static $instance = null;
	private $settings;

	private function __construct() 
	{
		$settings = array();
		
	}

	public function setConfigSettings($key, $contents) {
		$settings[$key] = $contents;
	}

	public function getConfigSetting($key) {
		return $settings[$key];
	}

	public static function getInstance() 
	{
		if (!self::$instance) 
		{
			self::$instance = new Calendar();
		}
		return self::$instance;
	}

}

?>

