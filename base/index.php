<?
/*
This file is part of the CourseUp project.
http://courseup.org

(c) Micah Taylor
micah@kixortech.com

See http://courseup.org for license information.
*/

$isCourseRoot = TRUE;
if(isset($resouce))
	$isCourseRoot = $resource == '';

if(isset($_GET['resource'])) {
	$resource = $_GET['resource'];
	$isCourseRoot = $resource == '';
}

//include '../CourseUp/Parsedown.php';
require_once('CourseUp/PDExtension.php');
require_once('CourseUp/htmlSchedule.php');
require_once('CourseUp/common.php');

$publicErrorMessages = $config['PublicErrorMessages'];
if($publicErrorMessages) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

include('header.htm');

{
	$contentPath = $resource . 'content.md';
	$f = file_get_contents($contentPath);
	$isScheduleDoc = false;
	$calendarKeyword = '/^\\\calendar\n/';
	$isScheduleDoc = preg_match($calendarKeyword, $f);

	if($isScheduleDoc)
	{
		$f = preg_replace($calendarKeyword, '', $f);
		print '<p><h3>';
		print $config['CourseTitle'];
		print '</h3></p>';
		print '<div class="scheduleTable">';
		$schedule = getFileHtmlSchedule($f);
		print $schedule;
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

