<?php
/*
This file is part of the CourseUp project.
http://courseup.org

(c) Micah Taylor
micah@kixortech.com

See http://courseup.org for license information.
*/
require_once('htmlSchedule.php');

class Config
{
	private static $instance = null;
	private static $ettings;

	private function __construct() 
	{
		self::$ettings = array();
	}

	public function loadSettings($configArray) {
		self::$ettings = $configArray;


		$tzName = self::$ettings['TimeZone'];
		$tz = new DateTimeZone($tzName);

		foreach(array_keys(self::$ettings) as $key) {
			$val = self::$ettings[$key];
			// $val = trim($val); TODO

			if(strpos($key, 'FirstQuarterDay') !== FALSE) {
				self::$ettings[$key] = DateTime::createFromFormat('Y-m-d', $val, $tz);
			}
			else if(strpos($key, 'LastBeforeBreak') !== FALSE) {
				self::$ettings[$key] = DateTime::createFromFormat('Y-m-d', $val, $tz);
			}
			else if(strpos($key, 'FirstAfterBreak') !== FALSE) {
				self::$ettings[$key] = DateTime::createFromFormat('Y-m-d', $val, $tz);
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
				self::$ettings[$key] = $ClassOnWeekDays;
			}
			else if(strpos($key, 'ShowPastSessions') !== FALSE) {
				self::$ettings[$key] = $val;
			}
			else if(strpos($key, 'ShowFutureSessions') !== FALSE) {
				self::$ettings[$key] = $val;
			} else if(strpos($key, 'DefaultView') !==
				FALSE) {
				self::$ettings[$key] = $val;
			} 
		}
	}

	public function setConfigSettings($key, $contents) {
		self::$ettings[$key] = $contents;
	}

	public function getConfigSetting($key) {
		if (in_array($key, array_keys(self::$ettings))) {
			return self::$ettings[$key];
		}
		return null;
	}

	public static function getInstance() 
	{
		if (!self::$instance) 
		{
			self::$instance = new Config();
		}
		return self::$instance;
	}

	public function buildParserArray() 
	{
		$parsers = array();
		$parsers['List'] = "fileGetHtmlScheduleCalendar";

		return $parsers;
	}

}

?>

