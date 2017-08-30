<?php

#require_once('Parsedown.php');
require_once('PDExtension.php');

$FirstQuarterDay = '';
$LastBeforeBreak = '';
$FirstAfterBreak = '';
$ClassOnWeekDays = '';

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

function getNextClassDay($currentDay)
{
	$oneDay = new DateInterval('P1D');
	$nextDay = clone $currentDay;
	$nextDay->add($oneDay);

	//if current day is not class weekday
	while( !isClassDay($nextDay) || onBreak($nextDay) )
		$nextDay->add($oneDay);

	return $nextDay;
}

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

function getConfigSetting($key)
{
	global $config;
	global $FirstQuarterDay;
	global $LastBeforeBreak;
	global $FirstAfterBreak;
	global $ClassOnWeekDays;
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

function getHtmlSchedule()
{
	getConfigSetting('FirstQuarterDay');
	getConfigSetting('LastBeforeBreak');
	getConfigSetting('FirstAfterBreak');
	getConfigSetting('ClassOnWeekDays');

	global $ClassOnWeekDays;

	date_default_timezone_set('UTC');

	$f = file_get_contents('schedule_data.txt');
	$f = removeCommentLines($f);
	$sessions = explode('Session:', $f);

	global $FirstQuarterDay;
	$currentDay = clone $FirstQuarterDay;
	$scheduleHtml = '';
	$itemsDue = Array();

	//http://stackoverflow.com/questions/6019845/show-hide-div-on-click-with-css
	$scheduleHtml .= '<label id="sessionToggleLabel" for="hidePastSessions">Toggle past sessions</label>';
	//$scheduleHtml .= '<input type="checkbox" checked>Hide past sessions</label>';
	$scheduleHtml .= '<input type="checkbox" id="hidePastSessions" checked>';
	$scheduleHtml .= "<div id=\"pastSessions\">\n\n";
	$now = new DateTime();
	$dayAndABit = new DateInterval('P1DT6H');
	$now->sub($dayAndABit);
	$pastSessionsDone = FALSE;

	#$daysInWeek = strlen($config['ClassOnWeekDays']);
	$daysInWeek = count($ClassOnWeekDays);

	for($i=1; $i<count($sessions); $i++)
	{
		if($currentDay > $now && !$pastSessionsDone) {
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

//$s = getHtmlSchedule();
//echo $s;

?>
