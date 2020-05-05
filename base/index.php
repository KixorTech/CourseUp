<?php
/*
This file is part of the CourseUp project.
http://courseup.org

(c) Micah Taylor
micah@kixortech.com

See http://courseup.org for license information.
*/

$resource = '';
if(isset($_GET['resource'])) {
	$resource = $_GET['resource'];
}

//include '../CourseUp/Parsedown.php';
require_once('CourseUp/PDExtension.php');
require_once('CourseUp/Calendar.php');
require_once('CourseUp/common.php');

$publicErrorMessages = false;//$config['PublicErrorMessages'];
if($publicErrorMessages) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

include('header.htm');

{
	$contentPath = $resource . 'content.md';
	// $indexPath = 'index.php';

	$f = '';
	// if(file_exists($indexPath)) {
	// 	$f = file_get_contents($indexPath);
	// 	//print 'index f: ' . $f;
	// }
	 if(file_exists($contentPath)) {
		$f = file_get_contents($contentPath);
		// print 'content f: ' . $f;
	}

	$isScheduleDoc = false;
	$calendarKeyword = '/^\\\\calendar\r|^\\\\calendar\n/'; // match begin line, lots of whitespace, \calendar, whitespace, end of line
	$isScheduleDoc = preg_match($calendarKeyword, $f); // find better way to detect \calendar

	if($isScheduleDoc)
	{
		$f = preg_replace($calendarKeyword, '', $f);
		$f = PDExtension::instance()->parseInput($f); 
		$f = removeCommentLines($f);
		Calendar::getInstance()->parseCalendarFile($f);

		print '<p><h3>';
		print Config::getInstance()->getConfigSetting('CourseTitle');
		print '</h3></p>';
		print '<div class="scheduleTable">';

		// ideally, we'd have a function in Config or common
		// that would allow us to cycle through
		// the different calendar display choices, but this is
		// a solution for now.
		print '<label id="formatToggleLabel" class="standardLabel" for="toggleCalendarFormat">Toggle Schedule Format</label>';
        print '<input id="toggleCalendarFormat" type="checkbox">';

		if(Config::getInstance()->getConfigSetting("DefaultView") == "Table") {
			print '<div id="Calendar1">';
			print $parsers["Table"]->parseCalendar();
			print '</div>';

			print '<div id="Calendar2">';
			print $parsers["List"]->parseCalendar();
			print '</div>';
		} else {			
			print '<div id="Calendar1">';
			print $parsers["List"]->parseCalendar();
			print '</div>';

			print '<div id="Calendar2">';
			print $parsers["Table"]->parseCalendar();
			print '</div>';
		}
		
		// Previous code: 
		// $schedule = $parser->parseCalendar();
		// print $schedule;
		print '</div>';
	}
	else
	{
		$p = PDExtension::instance()->text($f);
		//$p = ParseDown::instance()->text($f);
		echo $p;
	}
}

include('footer.htm');
?>

