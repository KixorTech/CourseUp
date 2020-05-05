<?php
require_once('CalendarView.php');

class ListView implements CalendarView
{
    function parseCalendar()
    {
        date_default_timezone_set('UTC');
    
        $cal = Calendar::getInstance();
        $config_obj = Config::getInstance();
        
        $currentDay = $config_obj->getConfigSetting('FirstQuarterDay');
        $scheduleHtml = "<div1 id='ListViewDiv'>";
        $itemsDue = Array();
    
//         // $scheduleHtml .= '<script src="include/jquery.min.js"></script>';
//         // $scheduleHtml .= '<script src="include/screen.js"></script>';
    
//         //http://stackoverflow.com/questions/6019845/show-hide-div-on-click-with-css
//         // //$scheduleHtml .= '<input type="checkbox" checked>Hide past sessions</label>';
        
//         //$scheduleHtml .= '<input type="checkbox" checked>Hide past sessions</label>';

        $scheduleHtml .= '<label id="top-PastSessionsLabel" class="standardLabel" for="hidePastSessions">Toggle past sessions</label>';
        $scheduleHtml .= '<input id="hidePastSessions" class="pastSessionsCheckbox" type="checkbox" >';

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
                // return $scheduleHtml;
    
            $dontMakeButton = $currentDay > $pastSessionTime && $i <= $ShowPastSessions && !$pastSessionsDone;
            if($dontMakeButton) {
                $scheduleHtml .= "</div>\n\n";
                $pastSessionsDone = TRUE;
                $scheduleHtml .= "<div id=\"currentSessions\">\n\n";
            }
            else if($currentDay > $pastSessionTime && !$pastSessionsDone) {
                $scheduleHtml .= "</div>\n\n";
                $scheduleHtml .= '<label id="bottom-PastSessionsLabel" class="standardLabel" for="hidePastSessions">Toggle past sessions' /*. '</label>'*/ ;
                $scheduleHtml .= '<input ' . /*'id="hidePastSessionsB"' .*/ 'class="pastSessionsCheckbox" type="checkbox" >';
                $scheduleHtml .= '</label>';
                // $scheduleHtml .= '<label class="sessionToggleLabel" id="sessionToggleLabelB"'>;
                // $scheduleHtml .= '<label class="sessionToggle" id="sessionToggleLabelB"  onclick="//document.getElementById(\'sessionToggleLabelB\').scrollIntoView(true)" for="pastSessionsCheckbox">Toggle Past Sessions</label>';
                $pastSessionsDone = TRUE;
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
        $scheduleHtml .= '</div>';
        $scheduleHtml .= '</div1>';
        echo $scheduleHtml;
//         // $s = ParsedownExtra::instance()->text($scheduleHtml); 
//         // echo $s;
//         //return $s;
    }
}

?>