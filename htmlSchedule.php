<?php
/*
This file is part of the CourseUp project.
http://courseup.org

(c) Micah Taylor
micah@kixortech.com

See http://courseup.org for license information.
*/

#require_once('Parsedown.php');
require_once('PDExtension.php');
include './Calendar.php';


//TODO move these to a default config setting
$FirstQuarterDay = '';
$LastBeforeBreak = '';
$FirstAfterBreak = '';
$ClassOnWeekDays = '';
$ShowPastSessions = 1;
$ShowFutureSessions = 3000000;

function getFile($path)
{
	$f = fopen($path, 'r');
   $s = '';

   if( !$f )
      return $s;

   while( ($line = fgets($f)) )
   {
      $s = $s . $line;
   }

   fclose($f);
   return $s;
}

function iterateToClassDay($currentDay, $direction)
{
	$oneDay = new DateInterval('P1D');
	$nextDay = clone $currentDay;
	if($direction < 0)
		$oneDay->invert = 1;
	$nextDay->add($oneDay);

	//if current day is not class weekday
	$numTries = 100;
	for($i=0; $i<$numTries && (!isClassDay($nextDay) || onBreak($nextDay) ); $i++)
		$nextDay->add($oneDay);

	return $nextDay;
}

function getPrevClassDay($currentDay)
{ return iterateToClassDay($currentDay, -1); }

function getNextClassDay($currentDay)
{ return iterateToClassDay($currentDay, 1); }

function removeCommentLines($string)
{
	$outS = '';
	$lines = explode("\n", $string);

	foreach($lines as $line)
	{
		$commented = 1 == preg_match('_\s*//.*_', $line);
		if( !$commented )
			$outS = $outS . $line . "\n";
	}
	return $outS;
}

//TODO this is brittle, invalid keys are not detected
//TODO extract this to a config object that maintains the default values
function getConfigSetting($key)
{
	global $config;
	global $FirstQuarterDay;
	global $LastBeforeBreak;
	global $FirstAfterBreak;
	global $ClassOnWeekDays;
	global $ShowPastSessions;
	global $ShowFutureSessions;

	$val = $config[$key];
	$val = trim($val);

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

class ItemDue {
	public $session;
	public $daysTillDue;
}

function getItemLink($s)
{
	$p = explode('*', $s);

	if(count($p) < 2)
		return "$s";

	$s = trim($p[1]);
	if($s[0] === '+')
	{
		$newItem = trim(substr($s, 1));
		$newItem = "<a href=\"$newItem\">$newItem</a>";
		return $p[0] . '* ' . $newItem;
	}

	return $p[0] . '*' . $p[1];
}

function getBulletList($string, $currentDay, &$itemsDue)
{
	$items = explode("\n", $string);

	$list = '';

	foreach($items as $item)
	{
		if(strlen($item) > 0)
		{
			$session = $item;
			if(strpos($session, 'due +'))
			{
				$p = explode('due +', $session);
				$session = $p[0];
				$session = getItemLink($session);
				$daysTillDue = $p[1];

				$itemDue = new ItemDue();
				$itemDue->session = $session;
				$itemDue->daysTillDue = $daysTillDue;
				$itemsDue[] = $itemDue;

				$endOfDay = new DateInterval('PT23H59M');
				$dueDate = $currentDay;
				for($d=0; $d<$daysTillDue; $d++)
					$dueDate = getNextClassDay($dueDate);
				$session = $session . ' (due ' .$dueDate->format('D M d') .')';
				//TODO fix timezone issue
				$dueDate->add($endOfDay);
				//$timer = '<script language="javascript">timer('. $dueDate->format('U') .');</script>';
				//$session = $session . $timer;
				$list = $list .$session."\n";
			}
			else
			{
				$list = $list . getItemLink($session)."\n";
			}

		}
	}

	foreach($itemsDue as $item)
	{
		if($item->daysTillDue == 0)
			$list = $list . '* Due: '.$item->session."\n";
	}

	return $list;
}

function onBreak($date)
{
	global $LastBeforeBreak;
	global $FirstAfterBreak;

	if($date->format('U') > $LastBeforeBreak->format('U') &&
		$date->format('U') < $FirstAfterBreak->format('U'))
		return TRUE;
	return FALSE;
}

function isClassDay($date)
{
	global $ClassOnWeekDays;
	$day = $date->format('D');

	foreach($ClassOnWeekDays as $d)
	{
		//print $d . ' ' . $day . "\n";
		if($day === $d)
			return TRUE;
	}
	return FALSE;
}

function getSessionHtml($session, $dayCount, &$currentDay, &$itemsDue)
{
	//$points = explode('Topic:', $session);
	//$prep = explode('Prep:', $topic[1]);
	//$due = explode('Due:', $prep[1]);
	//$topic = $prep[0];
	//$prep = $due[0];
	//$due = $due[1];


	$row = '';
	$row = $row . "\n**".$dayCount .': '.$currentDay->format('D M d'). "**\n";
	$row = $row . getBulletList($session, clone $currentDay, $itemsDue);

	$currentDay = getNextClassDay($currentDay);

	//TODO: replace this terrible hack with markdown
	$row = $row . "\n-------\n";

	return $row;
}

function getFileHtmlSchedule($fileContents)
{
	getConfigSetting('FirstQuarterDay');
	getConfigSetting('LastBeforeBreak');
	getConfigSetting('FirstAfterBreak');
	getConfigSetting('ClassOnWeekDays');
	getConfigSetting('ShowPastSessions');
	getConfigSetting('ShowFutureSessions');

	global $ShowPastSessions;
	global $ShowFutureSessions;
	global $ClassOnWeekDays;

	date_default_timezone_set('UTC');

	$f = $fileContents;
	$f = PDExtension::instance()->parseInput($f); 

	$f = removeCommentLines($f);
	$sessions = explode('Session:', $f);

	global $FirstQuarterDay;
	$currentDay = clone $FirstQuarterDay;
	$scheduleHtml = '';
	$itemsDue = Array();

	$scheduleHtml .= '<script src="include/jquery.min.js"></script>';
	$scheduleHtml .= '<script src="include/screen.js"></script>';

	//http://stackoverflow.com/questions/6019845/show-hide-div-on-click-with-css
	$scheduleHtml .= '<label id="formatToggleLabel" for="toggleCalendarFormat">Toggle grid format</label>';
	// //$scheduleHtml .= '<input type="checkbox" checked>Hide past sessions</label>';
	$scheduleHtml .= '<input type="checkbox" id="toggleCalendarFormat" checked>';
	
	$scheduleHtml .= '<label id="sessionToggleLabel" for="hidePastSessions">Toggle past sessions</label>';
	//$scheduleHtml .= '<input type="checkbox" checked>Hide past sessions</label>';
	$scheduleHtml .= '<input type="checkbox" id="hidePastSessions" checked>';
	$scheduleHtml .= "<div id=\"pastSessions\">\n\n";


	$now = new DateTime();
	$pastSessionTime = $now;
	$futureSessionTime = $now;
	$dayAndABit = new DateInterval('P1DT6H');
	$now->sub($dayAndABit);
	$pastSessionsDone = FALSE;

	for($i=0; $i<$ShowPastSessions; $i++)
		$pastSessionTime = getPrevClassDay($pastSessionTime);
	for($i=0; $i<$ShowFutureSessions; $i++)
		$futureSessionTime = getNextClassDay($futureSessionTime);

	#$daysInWeek = strlen($config['ClassOnWeekDays']);
	$daysInWeek = count($ClassOnWeekDays);

	for($i=1; $i<count($sessions); $i++)
	{
		if($currentDay > $futureSessionTime)
			return $scheduleHtml;
		if($currentDay > $pastSessionTime && !$pastSessionsDone) {
			$scheduleHtml .= "</div>\n\n";
			$pastSessionsDone = TRUE;
		}

		$sessionHtml = getSessionHtml($sessions[$i], $i, $currentDay, $itemsDue);

		$endOfWeek =  $i > 0 && $i % $daysInWeek == 0;
		if($endOfWeek) {
			$sessionHtml = $sessionHtml . "\n-------\n";
		}

		$sessionHtml = PDExtension::instance()->text($sessionHtml); 
		$scheduleHtml = $scheduleHtml . $sessionHtml;
		for($j=0; $j<count($itemsDue); $j++)
			$itemsDue[$j]->daysTillDue--;
	}

	return $scheduleHtml;
	//$s = ParsedownExtra::instance()->text($scheduleHtml); 
	//return $s;
}

function getHtmlSchedule()
{
	$f = file_get_contents('schedule_data.txt');
	return getFileHtmlSchedule($f);
}

//$s = getHtmlSchedule();
//echo $s;

?>
