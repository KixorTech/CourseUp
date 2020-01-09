<?php
require_once('htmlSchedule.php');
require_once('common.php');

// the following function was reference from
// https://stackoverflow.com/questions/14643617/create-table-using-javascript
class CalendarTable implements CalendarView {

function parseCalendar() {
	$cal = Calendar::getInstance();
	$config = Config::getInstance();

	$classDaysPerWeek = $config->getConfigSetting("ClassOnWeekDays");
	$currentDay = $config->getConfigSetting("FirstQuarterDay");
	$lastDay = $config->getConfigSetting("LastQuarterDay");

    $dom = new DOMDocument();
	$tblDiv = $dom->createElement('div');
	$tblDiv->setAttribute('id', 'newCalendarDiv');

	$attributeNames = ['Assignments'];
	
	// ===== Making the header: 
	$tbl = $dom->createElement('table');
	$tbl->setAttribute('style', 'width:100%;');
	$tbl->setAttribute('border', '1px solid #aaa;');
	$tbdy = $dom->createElement('tbody');

	$headerRow = $dom->createElement('tr');
	$th = $dom->createElement('th');
	$th->textContent = "Week";
	$headerRow->appendChild($th);

	$th = $dom->createElement('th');
	$th->textContent = "Session";
	$headerRow->appendChild($th);

	for ($i = 0; $i < sizeof($attributeNames); $i++){
		$th = $dom->createElement('th');
		$th->textContent = $attributeNames[$i];
		$headerRow->appendChild($th);
	}
	$tbdy->appendChild($headerRow);
	
	// ===== Making the table:
	$w=0;
	while ($currentDay <= $lastDay) {
		for ($d = 0; $d < sizeof($classDaysPerWeek); $d++) {
			$tr = $dom->createElement('tr');
			for ($c = 0; $c < sizeof($attributeNames)+2; $c++) {
				$td = $dom->createElement('td');
				$text = '';
				if ($c == 0 && $d == 0) {
					$text = "Week " . ($w + 1);
				}
				if ($c == 1) {
					$text = ($d + $w*sizeof($classDaysPerWeek) + 1) . ": " . $currentDay->format('D M d');
					// $text = ($d + $w*sizeof($classDaysPerWeek) + 1) . ": " . $classDaysPerWeek[$d];
				}
				$td->textContent = $text;
				$tr->appendChild($td);
			}
			$tbdy->appendChild($tr);

			$currentDay = getNextClassDay($currentDay);
			if ($currentDay > $lastDay) break;
		}
		$w += 1;
	}
	$tbl->appendChild($tbdy);
	$tblDiv->appendChild($tbl);
	$dom->appendChild($tblDiv);
	
	echo $dom->saveHTML();
}
}
?>