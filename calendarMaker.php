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

	// $dom = new DOMDocument();
	
	$HTMLString = '<div id="newCalendarDiv">';
	// $tblDiv = $dom->createElement('div');
	// $tblDiv->setAttribute('id', 'newCalendarDiv');

	$attributeNames = ['Assignments'];
	
	// ===== Making the header: 
	
	$HTMLString = $HTMLString . '<table style="width:100%", border="1px solid #aaa">';
	// $tbl = $dom->createElement('table');
	// $tbl->setAttribute('style', 'width:100%;');
	// $tbl->setAttribute('border', '1px solid #aaa;');

	$HTMLString = $HTMLString . '<tbody>';
	// $tbdy = $dom->createElement('tbody');

	$HTMLString = $HTMLString . '<tr>';
	// $headerRow = $dom->createElement('tr');

	$HTMLString = $HTMLString . '<th>Week</th>';
	// $th = $dom->createElement('th');
	// $th->textContent = "Week";
	// $headerRow->appendChild($th);

	$HTMLString = $HTMLString . '<th>Session</th>';
	// $th = $dom->createElement('th');
	// $th->textContent = "Session";
	// $headerRow->appendChild($th);

	for ($i = 0; $i < sizeof($attributeNames); $i++){
		$HTMLString = $HTMLString . '<th>' . $attributeNames[$i] . '</th>';
		// $th = $dom->createElement('th');
		// $th->textContent = $attributeNames[$i];
		// $headerRow->appendChild($th);
	}
	$HTMLString = $HTMLString . '</tr>';
	// $tbdy->appendChild($headerRow);
	
	// ===== Making the table:
	$w=0; #week
	$s=1; #session
	$itemsDue = Array();
	while ($currentDay <= $lastDay) {
		for ($d = 0; $d < sizeof($classDaysPerWeek); $d++) {
			$HTMLString = $HTMLString . '<tr>';
			// $tr = $dom->createElement('tr');

			for ($c = 0; $c < sizeof($attributeNames)+2; $c++) {
				$HTMLString = $HTMLString . '<td>';
				// $td = $dom->createElement('td');

				// $text = '';
				if ($c == 0 && $d == 0) {
					$HTMLString = $HTMLString . "Week " . ($w + 1);
					// $text = "Week " . ($w + 1);
					// $td->textContent = $text;
				}
				else if ($c == 1) {
					$HTMLString = $HTMLString . ($d + $w*sizeof($classDaysPerWeek) + 1) . ": " . $currentDay->format('D M d');
					// $text = ($d + $w*sizeof($classDaysPerWeek) + 1) . ": " . $currentDay->format('D M d');
					// //$text = ($d + $w*sizeof($classDaysPerWeek) + 1) . ": " . $classDaysPerWeek[$d];
					// $td->textContent = $text;
				}
				else if ($c == 2){
					
					$sessionHtml = getBulletList($cal->getSession($s), clone $currentDay, $itemsDue);
					$sessionHtml = PDExtension::instance()->text($sessionHtml);
					$HTMLString = $HTMLString . $sessionHtml;
					$s++;
				}
				
				$HTMLString = $HTMLString . '</td>';
				// $tr->appendChild($td);
			}
			$HTMLString = $HTMLString . '</tr>';
			// $tbdy->appendChild($tr);

			$currentDay = getNextClassDay($currentDay);
			if ($currentDay > $lastDay) break;
		}
		$w += 1;
	}
	$HTMLString = $HTMLString . '</tbody>';
	// $tbl->appendChild($tbdy);

	$HTMLString = $HTMLString . '</table>';
	// $tblDiv->appendChild($tbl);

	$HTMLString = $HTMLString . '</div>';
	// $dom->appendChild($tblDiv);
	
	echo $HTMLString;
}
}
?>