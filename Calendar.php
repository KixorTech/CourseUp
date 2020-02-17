<?php
/*
This file is part of the CourseUp project.
http://courseup.org

(c) Micah Taylor
micah@kixortech.com

See http://courseup.org for license information.
*/


require_once('PDExtension.php');
require_once('Config.php');
require_once('common.php');
require_once('helpers.php');

class Calendar
{
	private static $instance = null;
	private static $events;
	private static $ses_num;
	private static $due_list;

	private function __construct() 
	{
		self::$events = array();
		self::$due_list = array();
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

	// DONE: Change how the item's name is stored, see if it is a url and store that url if available
	public static function addToDueList($itemDue, $dueDay) {
		self::$due_list[$itemDue] = $dueDay;
	}
}

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
			if(strpos($session, 'due'))
			{
				if (!strpos($session, 'due +') &&
						!preg_match('/.+ due \d\d\d\d-\d\d-\d\d*/', $session)) {
					print 'Incorrect Due Date Formatting';
					return;
				}

				$p = explode('due ', $session);
				$p[1] = ltrim($p[1], '+');
				$session = $p[0];
				$session = getItemLink($session);
				// print_r($p);

				$assignmentDate = null;
				if (strpos($p[1], '.')) {
					$assignmentDate = explode('.', $p[1])[0];
					$daysTillDue = getSessionDueDate($assignmentDate, $currentDay);
					
					$hourDue = explode('.', $p[1])[1];
					$hourDue = date("g:i a", strtotime($hourDue));
				} else {
					$daysTillDue = getSessionDueDate($p[1], $currentDay);
					$hourDue = -1;
				}
				
				$itemDue = new ItemDue();
				$itemDue->session = $session;
				$itemDue->daysTillDue = $daysTillDue;
				$itemDue->timeDue = $hourDue;
				$itemDue->nonClassDue = $assignmentDate;
				$itemsDue[] = $itemDue;

				$endOfDay = new DateInterval('PT23H59M');
				$dueDate = $currentDay;
				for($d=0; $d<$daysTillDue; $d++) {
					$dueDate = getNextClassDay($dueDate);
				}

				if (isset($itemDue->nonClassDue)) {
					$date = $itemDue->nonClassDue;
					$session = $session . ' (due ' . date_format($date, 'D M d');
				} else {
					$session = $session . ' (due ' .$dueDate->format('D M d');
				}
				if ($hourDue != -1) {
					$session = $session . ' at '. $hourDue;
				}
				$session = $session . ')';
				
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
		if($item->daysTillDue == 0) {
			$list = $list . '* <b>Due:</b> '.trim($item->session, ' *');
			addItemDueToCalendarDueList($item, $currentDay);
			if (isset($item->nonClassDue)) {
				$date = $item->nonClassDue;
				$list = $list . ' on <b>' . date_format($date, 'D M d').'</b>';
			}
			if ($item->timeDue != -1) {
				$list = $list . ' at '.trim($item->timeDue);
			}
			$list = $list ."\n";
		}
	}
	return $list;
}

function addItemDueToCalendarDueList($item, $currentDay) {
	$rawText = $item->session;
	$url = NULL;
	$pathToResource = NULL;
	if (preg_match("/\[.*\]\(.*\)/", $rawText)) {
		$name = getStringBetween($rawText, "[", "]");
		$pathToResource = getStringBetween($rawText, "(", ")");
	}
	else if (preg_match('/<a href=".*">.*<\/a>/', $rawText)) {
		$name = getStringBetween($rawText, "\">", "</a>");
		$pathToResource = "/".getStringBetween($rawText, "<a href=\"", "\">");
	}
	$url = getHost().$pathToResource;

	if (isset($pathToResource)) {
		if (isset($item->nonClassDue)) {
			$date = date_format($item->nonClassDue, 'D M d');
			if ($item->timeDue != -1) {
				$date = $date." at ".trim($item->timeDue);
			}
			Calendar::getInstance()->addToDueList($url, $date);
		} else if ($item->timeDue != -1) {
			$date = date_format($currentDay, 'D M d'). ' at '.trim($item->timeDue);
			Calendar::getInstance()->addToDueList(trim($url, ' *'), $date);
		} else {
			$date = date_format($currentDay, 'D M d');
			Calendar::getInstance()->addToDueList(trim($url, ' *'), $date);
		}
	}
}

function getStringBetween($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

function getSessionDueDate(&$dueDate, $currentDay) {
	if (preg_match('/\d\d\d\d-\d\d-\d\d/', $dueDate)) {
		$dueDate = new DateTime($dueDate);
		$days = 0;
		while ($currentDay < $dueDate) {
			$currentDay = getNextClassDay($currentDay);
			$days++;
		}
		$diff = $currentDay->diff( $dueDate );
		$diffDays = (integer)$diff->format( "%R%a" );
		if ($diffDays >= 0) {
			$dueDate = null;
		} else {
			$days--;
		}
		
	} else {
		$days = $dueDate;
		$dueDate = null;
	}
	return $days;
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

function getSessionHtml($dayCount, &$currentDay, &$weekCount, &$itemsDue)
{
	//$points = explode('Topic:', $session);
	//$prep = explode('Prep:', $topic[1]);
	//$due = explode('Due:', $prep[1]);
	//$topic = $prep[0];
	//$prep = $due[0];
	//$due = $due[1];

	$session = Calendar::getInstance()->getSession($dayCount);
	$row = '';
	$row = $row . "\n**".$dayCount .': '.$currentDay->format('D M d'). "** <span class=\"weekCount\">".$weekCount."</span>\n";
	$row = $row . getBulletList($session, clone $currentDay, $itemsDue);

	//TODO: replace this terrible hack with markdown
	$row = $row . "\n-------\n";

	return $row;
}

class ItemDue {
	public $session;
	public $daysTillDue;
	public $timeDue;
	public $nonClassDue;
}

?>

