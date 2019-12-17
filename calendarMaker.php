<?php
require_once('htmlSchedule.php');

// the following function was reference from
// https://stackoverflow.com/questions/14643617/create-table-using-javascript
function tableCreate() {
	global $ClassOnWeekDays;

    $dom = new DOMDocument();
	$tblDiv = $dom->createElement('div');
	$tblDiv->setAttribute('id', 'newCalendarDiv');

	// Once we have the datasource figured out, the following attributes
	// will be retrieved from there.
	$numWeeks = 5;
	$classDaysPerWeek = $ClassOnWeekDays;
	$attributeNames = ['Homework Assignments', 'Labs', 'Readings'];
	

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
	$th->textContent = "Day";
	$headerRow->appendChild($th);

	for ($i = 0; $i < sizeof($attributeNames); $i++){
		$th = $dom->createElement('th');
		$th->textContent = $attributeNames[$i];
		$headerRow->appendChild($th);
	}
	$tbdy->appendChild($headerRow);
	
	// ===== Making the table:
	for ($w = 0; $w < $numWeeks; $w++) {
		for ($d = 0; $d < sizeof($classDaysPerWeek); $d++) {
			$tr = $dom->createElement('tr');
			for ($c = 0; $c < sizeof($attributeNames)+2; $c++) {
				$td = $dom->createElement('td');
				$text = '';
				if ($c == 0 && $d == 0) {
					$text = "Week " . $w;
				}
				if ($c == 1) {
					$text = "Day " . ($d + $w*sizeof($classDaysPerWeek));
				}
				$td->textContent = $text;
				$tr->appendChild($td);
			}
			$tbdy->appendChild($tr);
		}
	}
	$tbl->appendChild($tbdy);
	$tblDiv->appendChild($tbl);
	$dom->appendChild($tblDiv);
	
	echo $dom->saveHTML();
}
?>