<?php
/*
This file is part of the CourseUp project.
http://courseup.org

(c) Micah Taylor
micah@kixortech.com

See http://courseup.org for license information.
*/


require_once('common.php');

class Calendar
{
	private static $instance = null;
	private static $events;
	private static $ses_num;

	private function __construct() 
	{
		self::$events = array();
	}

	public function parseCalendarFile($f) 
	{
		$sessions = explode('Session:', $f);
		array_shift($sessions);

		self::$ses_num = 0;
		
		foreach($sessions as $ses) {
			self::$ses_num++;
			self::$events[self::$ses_num] = $ses;
		}
	}

	public function getSession($day) 
	{
		return self::$events[$day];
	}

	public function numSessions()
	{
		return self::$ses_num;
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

