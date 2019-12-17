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
require_once('Calendar.php');
require_once('Config.php');

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

function getNextDay($currentDay, $direction=1)
{
	$oneDay = new DateInterval('P1D');
	$nextDay = clone $currentDay;
	if($direction < 0)
		$oneDay->invert = 1;
	$nextDay->add($oneDay);

	return $nextDay;

}

function iterateToClassDay($currentDay, $direction)
{
	$nextDay = getNextDay($currentDay, $direction);

	//if current day is not class weekday
	$numTries = 100;
	for($i=0; $i<$numTries && (!isClassDay($nextDay) || onBreak($nextDay) ); $i++)
		$nextDay = getNextDay($nextDay, $direction);

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
	$LastBeforeBreak = Config::getInstance()->getConfigSetting('LastBeforeBreak');
	$FirstAfterBreak = Config::getInstance()->getConfigSetting('FirstAfterBreak');

	if($date->format('U') > $LastBeforeBreak->format('U') &&
		$date->format('U') < $FirstAfterBreak->format('U'))
		return TRUE;
	return FALSE;
}

function isLastDayBeforeBreak($date)
{
	$LastBeforeBreak = Config::getInstance()->getConfigSetting('LastBeforeBreak');
	if($date->format('U') == $LastBeforeBreak->format('U'))
		return TRUE;
	return FALSE;
}

function isClassDay($date)
{
	$ClassOnWeekDays = Config::getInstance()->getConfigSetting('ClassOnWeekDays');
	$day = $date->format('D');

	foreach($ClassOnWeekDays as $d)
	{
		//print $d . ' ' . $day . "\n";
		if($day === $d)
			return TRUE;
	}
	return FALSE;
}

function getSessionHtml($session, $dayCount, &$currentDay, &$weekCount, &$itemsDue)
{
	//$points = explode('Topic:', $session);
	//$prep = explode('Prep:', $topic[1]);
	//$due = explode('Due:', $prep[1]);
	//$topic = $prep[0];
	//$prep = $due[0];
	//$due = $due[1];


	$row = '';
	$row = $row . "\n**".$dayCount .': '.$currentDay->format('D M d'). "** <span class=\"weekCount\">".$weekCount."</span>\n";
	$row = $row . getBulletList($session, clone $currentDay, $itemsDue);

	//TODO: replace this terrible hack with markdown
	$row = $row . "\n-------\n";

	return $row;
}

function fileGetHtmlScheduleCalendar($fileContents)
{
	date_default_timezone_set('UTC');

	$f = $fileContents;

	$f = PDExtension::instance()->parseInput($f); 
	$f = removeCommentLines($f);

	$cal = Calendar::getInstance();
	$cal->parseCalendarFile($f);
	$config_obj = Config::getInstance();
	
	$sessions = explode('Session:', $f);

	$currentDay = $config_obj->getConfigSetting('FirstQuarterDay');
	$scheduleHtml = '';
	$itemsDue = Array();

	//http://stackoverflow.com/questions/6019845/show-hide-div-on-click-with-css
	$scheduleHtml .= '<input type="checkbox" id="pastSessionsCheckbox" checked>';
	$scheduleHtml .= '<label class="sessionToggle" id="sessionToggleLabelA" onclick="//document.getElementById(\'sessionToggleLabelB\').scrollIntoView(true)" for="pastSessionsCheckbox">Toggle past sessions</label>';
	//$scheduleHtml .= '<input type="checkbox" checked>Hide past sessions</label>';
	$scheduleHtml .= "<div id=\"pastSessionContent\">\n\n";

	$now = new DateTime();
	$pastSessionTime = $now;
	$futureSessionTime = $now;
	$dayAndABit = new DateInterval('P1DT6H');
	$now->sub($dayAndABit);
	$pastSessionsDone = FALSE;

	$ShowPastSessions = $config_obj->getConfigSetting('ShowPastSessions');
	$ShowFutureSessions = $config_obj->getConfigSetting('ShowFutureSessions');

	for($i=0; $i<$ShowPastSessions; $i++)
		$pastSessionTime = getPrevClassDay($pastSessionTime);
	for($i=0; $i<$ShowFutureSessions; $i++)
		$futureSessionTime = getNextClassDay($futureSessionTime);

	$daysInWeek = count($config_obj->getConfigSetting('ClassOnWeekDays'));
	$weekCount = 1;

	for($i=1; $i<$cal->numSessions(); $i++)
	{
		if($currentDay > $futureSessionTime)
			return $scheduleHtml;

		$dontMakeButton = $currentDay > $pastSessionTime && $i <= $ShowPastSessions && !$pastSessionsDone;
		if($dontMakeButton) {
			$scheduleHtml .= "</div>\n\n";
			$pastSessionsDone = TRUE;
		}
		else if($currentDay > $pastSessionTime && !$pastSessionsDone) {
			$scheduleHtml .= "</div>\n\n";
			$scheduleHtml .= '<label class="sessionToggle" id="sessionToggleLabelB"  onclick="//document.getElementById(\'sessionToggleLabelB\').scrollIntoView(true)" for="pastSessionsCheckbox">Toggle past sessions</label>';
			$pastSessionsDone = TRUE;
		}

		$sessionHtml = getSessionHtml($cal->getSession($i), $i, $currentDay, $weekCount, $itemsDue);

		$endOfWeek =  $i > 0 && $i % $daysInWeek == 0;
		if($endOfWeek) {
			$sessionHtml = $sessionHtml . "\n-------\n";
			$weekCount++;
		}

		if(isLastDayBeforeBreak($currentDay)) {
			$sessionHtml = $sessionHtml . "<span class=\"breakMark\">Break</span>\n\n-------\n-------\n";
		}

		$sessionHtml = PDExtension::instance()->text($sessionHtml); 
		$scheduleHtml = $scheduleHtml . $sessionHtml;
		for($j=0; $j<count($itemsDue); $j++)
			$itemsDue[$j]->daysTillDue--;

		$currentDay = getNextClassDay($currentDay);
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
