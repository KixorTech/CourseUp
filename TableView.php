<?php
require_once('CalendarView.php');

class TableView implements CalendarView {
	
	function parseCalendar() {
	// we used the following source as reference:
	// https://stackoverflow.com/questions/14643617/create-table-using-javascript

	$cal = Calendar::getInstance();
	$config = Config::getInstance();

	$classDaysPerWeek = $config->getConfigSetting("ClassOnWeekDays");
	$currentDay = $config->getConfigSetting("FirstQuarterDay");
	$lastDay = $config->getConfigSetting("LastQuarterDay");
	$attributeNames = ['Assignments'];
	$HTMLString = '<div id="TableViewDiv">';
	
	// ===== Making the header: (TODO move into CSS?)
	$HTMLString .= '<div id="tableViewEntire">';
	$HTMLString .= '<table style="width:100%", border="1px solid #aaa">';
	$HTMLString .= '<tbody>';
	$HTMLString .= '<tr bgcolor="#A4A4A4">';
	$HTMLString .= '<th>Week</th>';
	$HTMLString .= '<th>Session</th>';

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
				if ($c==0 && $currentDay->format('D') == $classDaysPerWeek[0]){
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
				$HTMLString .= '</td>';
			}
			$HTMLString .= '</tr>';

			if(isLastDayBeforeBreak($currentDay)) {
				$HTMLString .= '<tr bgcolor="lightgreen"> <td colspan="3"; align="center"> <b> Break </b> </td>  </tr>';
            }
			
			$currentDay = getNextClassDay($currentDay);
			if ($currentDay > $lastDay) break;
		}
		$w += 1;
	}
	$HTMLString .= '</tbody>';
	$HTMLString .= '</table>';
	$HTMLString .= '</div>'; // closes TableViewEntire div
	$HTMLString .= '</div>'; // closes TableViewDiv div
	return $HTMLString;
}
}
?>