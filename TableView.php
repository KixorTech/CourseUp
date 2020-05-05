<?php
require_once('CalendarView.php');

// the following function was reference from
// https://stackoverflow.com/questions/14643617/create-table-using-javascript
class TableView implements CalendarView {

function parseCalendar() {
	$cal = Calendar::getInstance();
	$config = Config::getInstance();

	$classDaysPerWeek = $config->getConfigSetting("ClassOnWeekDays");
	$currentDay = $config->getConfigSetting("FirstQuarterDay");
	$lastDay = $config->getConfigSetting("LastQuarterDay");
	$attributeNames = ['Assignments'];
	$HTMLString = '<div id="newCalendarDiv">';
	
	// ===== Making the header: 
	$HTMLString = $HTMLString . '<div id="tableViewEntire">';
	$HTMLString = $HTMLString . '<table style="width:100%", border="1px solid #aaa">';
	$HTMLString = $HTMLString . '<tbody>';
	$HTMLString = $HTMLString . '<tr bgcolor="#A4A4A4">';
	$HTMLString = $HTMLString . '<th>Week</th>';
	$HTMLString = $HTMLString . '<th>Session</th>';

	for ($i = 0; $i < sizeof($attributeNames); $i++){
		$HTMLString = $HTMLString . '<th>' . $attributeNames[$i] . '</th>';
	}
	$HTMLString = $HTMLString . '</tr>';
	
	// ===== Making the table:
	$w=0; #week
	$s=1; #session
	$itemsDue = Array();
	while ($currentDay <= $lastDay) {
		for ($d = 0; $d < sizeof($classDaysPerWeek); $d++) {
			$HTMLString = $HTMLString . '<tr>';
			for ($c = 0; $c < sizeof($attributeNames)+2; $c++) {
				$HTMLString = $HTMLString . '<td>';
				// if ($c == 0 && $d == 0) {
				if ($c==0 && $currentDay->format('D') == $classDaysPerWeek[0]){ // TODO: make this the first day of classes
					$HTMLString = $HTMLString . "Week " . ($w + 1);
				}
				else if ($c == 1) {
					$HTMLString = $HTMLString . ($d + $w*sizeof($classDaysPerWeek) + 1) . ": " . $currentDay->format('D M d');
				}
				else if ($c == 2){
					$sessionHtml = getBulletList($cal->getSession($s), clone $currentDay, $itemsDue);
					$sessionHtml = PDExtension::instance()->text($sessionHtml);
					$HTMLString = $HTMLString . $sessionHtml;
					$s++;
					for($j=0; $j<count($itemsDue); $j++)
                		$itemsDue[$j]->daysTillDue--;
				}
				$HTMLString = $HTMLString . '</td>';
			}
			$HTMLString = $HTMLString . '</tr>';

			if(isLastDayBeforeBreak($currentDay)) {
				$HTMLString = $HTMLString . '<tr bgcolor="lightgrey"> <td colspan="3"; align="center"> <b> BREAK </b> </td>  </tr>';
            }
			
			$currentDay = getNextClassDay($currentDay);
			if ($currentDay > $lastDay) break;
		}
		$w += 1;
	}
	$HTMLString = $HTMLString . '</tbody>';
	$HTMLString = $HTMLString . '</table>';
	$HTMLString = $HTMLString . '</div>';	
	$HTMLString = $HTMLString . '</div>';
	echo $HTMLString;
}
}
?>