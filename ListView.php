<?php
/*
This file is part of the CourseUp project.
http://courseup.org

Authors: Tyson Clark and Olivia Penry

See http://courseup.org for license information.
*/
require_once('CalendarView.php');

class ListView implements CalendarView
{
function parseCalendar() {
    date_default_timezone_set('UTC');

    $cal = Calendar::getInstance();
    $config_obj = Config::getInstance();
    
    $currentDay = $config_obj->getConfigSetting('FirstQuarterDay');
    $scheduleHtml = "<div id='ListViewDiv'>";
    $itemsDue = Array();

    // ========= The following checkbox looks like a button and toggles visibility of the pastSessionContent div
    $scheduleHtml .= '<label id="top-PastSessionsLabel" class="standardLabel" for="hidePastSessions">Toggle Past Sessions</label>';
    $scheduleHtml .= '<input id="hidePastSessions" class="pastSessionsCheckbox" type="checkbox">';

    // ========= Start filling in the past sessions in the pastSessionContent div
    $scheduleHtml .= "<div id='pastSessionContent'>";

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
            break;

        $dontMakeButton = $currentDay > $pastSessionTime && $i <= $ShowPastSessions && !$pastSessionsDone;
        if($dontMakeButton) {
            $scheduleHtml .= "</div>\n\n"; // closes pastSessionContent div
            $pastSessionsDone = TRUE;
            $scheduleHtml .= "<div id=\"currentSessions\">\n\n";
        }
        else if($currentDay > $pastSessionTime && !$pastSessionsDone) {
            $scheduleHtml .= "</div>\n\n"; // closes pastSessionContent div
            $pastSessionsDone = TRUE;
            // ========= The following checkbox functions the same as top-PastSessionsLabel but is after pastSessionContent
            $scheduleHtml .= '<label id="bottom-PastSessionsLabel" class="standardLabel" for="hidePastSessions">'
                . 'Toggle past sessions'
                . '<input class="pastSessionsCheckbox" type="checkbox">' 
                . '</label>';
            $scheduleHtml .= "<div id=\"currentSessions\">\n\n";
        }

        $sessionHtml = getSessionHtml($i, $currentDay, $weekCount, $itemsDue);

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
    $scheduleHtml .= '</div>'; // closes currentSessions div
    $scheduleHtml .= '</div>'; // closes ListViewDiv div
    echo $scheduleHtml;
}
}
?>