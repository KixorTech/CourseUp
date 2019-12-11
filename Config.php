<?php
/*
This file is part of the CourseUp project.
http://courseup.org

(c) Micah Taylor
micah@kixortech.com

See http://courseup.org for license information.
*/

class Config
{
	private static $instance = null;
	private $ettings;

	private function __construct() 
	{
		$ettings = array();
	}

	public function loadSettings($configArray) {
		$ettings = array($configArray);

		foreach($ettings as $configItem) {

		}

		$tzName = $config['TimeZone'];
		$tz = new DateTimeZone($tzName);
		if(strpos($key, 'FirstQuarterDay') !== FALSE) {
			$FirstQuarterDay = DateTime::createFromFormat('Y-m-d', $val, $tz);
		}
		else if(strpos($key, 'LastBeforeBreak') !== FALSE) {
			$LastBeforeBreak = DateTime::createFromFormat('Y-m-d', $val, $tz);
		}
		else if(strpos($key, 'FirstAfterBreak') !== FALSE) {
			$FirstAfterBreak = DateTime::createFromFormat('Y-m-d', $val, $tz);
		}
		else if(strpos($key, 'ClassOnWeekDays') !== FALSE) {
			$ClassOnWeekDays = array();
			$days = strtolower($val);
			//print $val .' '. $days;
			if( strpos($days, 'm') !== FALSE)
				array_push($ClassOnWeekDays, 'Mon');
			if( strpos($days, 't') !== FALSE)
				array_push($ClassOnWeekDays, 'Tue');
			if( strpos($days, 'w') !== FALSE)
				array_push($ClassOnWeekDays, 'Wed');
			if( strpos($days, 'r') !== FALSE)
				array_push($ClassOnWeekDays, 'Thu');
			if( strpos($days, 'f') !== FALSE)
				array_push($ClassOnWeekDays, 'Fri');
			//print_r($ClassOnWeekDays);
		}
		else if(strpos($key, 'ShowPastSessions') !== FALSE) {
			$ShowPastSessions = $val;
		}
		else if(strpos($key, 'ShowFutureSessions') !== FALSE) {
			$ShowFutureSessions = $val;
		}
	}

	public function setConfigSettings($key, $contents) {
		$ettings[$key] = $contents;
	}

	public function getConfigSetting($key) {
		return $ettings[$key];
	}

	public static function getInstance() 
	{
		if (!self::$instance) 
		{
			self::$instance = new Config();
		}
		return self::$instance;
	}

}

?>

