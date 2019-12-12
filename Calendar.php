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
	private $events;

	private function __construct() 
	{
		$events = array();
	}

	public function parseCalendarFile($f) 
	{
		$sessions = explode('Session:', $f);
		array_shift($sessions);

		$ses_num = 0;
		
		foreach($sessions as $ses) {
			$ses_num++;
			$events[$ses_num] = $ses;
		}
		// for ($x = 1; $x <= $ses_num; $x++) {
		// 	print 'Session ' . $x . ':';
		// 	print '<br>';
		// 	print $events[$x];
		// 	print '<br><br>';
		// }
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

