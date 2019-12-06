<?php

namespace eu\freeplace\php\calendar;

/**
 * @author Elmar Dott
 */
class CalandarTableView {

    private $day;
    private $month;
    private $monthName;
    private $year;
    private $headline;
    private $header;
    private $holidays;
    private $rows;

    public function __construct($headline) {
        $this->headline = $headline;
    }

    public function drawGrid() {
        return "<div style='width: 150px; height: 150px; border: 1px dotted red;'>"
                . "<h1 style='font-size: 14px; width: 100%; text-align: center; background-color: yellow;'>"
                . $this->headline . "</h1>"
                . "<table align='center' border='0' cellpadding='0' cellspacing='0'
                            style='font-size: 12px; color: black; background-color: beige; text-align: center; border: 1px solid wheat;'>"
                . $this->header
                . $this->rows
                . "</table>"
                . "<p style='font-size: 12px; width: 100%; text-align: center; background-color: yellow;'>"
                . $this->monthName . "&nbsp; " . $this->year . "</p>"
                . "</div>";
    }

    public function header($days, $monthName) {
        $this->monthName = $monthName;
        $this->header = "<tr>
        <th width='20'>" . $days[0] . "</th>
        <th width='20'>" . $days[1] . "</th>
        <th width='20'>" . $days[2] . "</th>
        <th width='20'>" . $days[3] . "</th>
        <th width='20'>" . $days[4] . "</th>
        <th width='20'>" . $days[5] . "</th>
        <th width='20' style='color: red;'>" . $days[6] . "</th>
      </tr>";
    }

    public function setCurrentDate($day, $month, $year) {
        $this->day = $day;
        $this->month = $month;
        $this->year = $year;
    }

    public function setHolidays($holidays) {
        $this->holidays = $holidays;
    }

    public function calculateRows($start, $end) {
        $calendarGrid = null;
        $counter = 1;
        $space_cnt = 1;
        $holiday = $this->holidays;

        while ($counter <= $end) {
            $calendarGrid .= "\n<tr>";

            for ($j = 1; $j <= 7; $j++) { //Maximal 7 days
                ####################################################################
                //Format days
                $style = "";
                $class = "";
                $title = "";

                //sunday
                if (($j == 7) && ($counter < $end) || ($counter == $start)) {
                    $style .= " color:red;";
                    $class .= "sundays ";
                }
                //holiday
                if (isset($holiday[$this->month][$counter][1])) {
                    if ($holiday[$this->month][$counter][1] == '1') {
                        $style .= " color:red;";
                    } elseif ($holiday[$this->month][$counter][1] == '2') {
                        $style .= " color:blue;";
                    }
                    $style .= " font-weight:bold;";
                    $class .= "holidays ";
                    $title = $holiday[$this->month][$counter][0];
                }
                //current day
                if ($this->day == $counter) {
                    $style .= " border:1px solid;";
                    $class .= " currentDay ";
                }

                if ($style != "") {
                    $style = " style='" . $style . "'";
                }
                if ($class != "") {
                    $class = " class='" . $class . "'";
                }

                // create cells
                $calendarGrid .= "\n\t<td" . $style . $class . ">";
                if ($space_cnt < $start) {
                    $calendarGrid .= "&nbsp;";
                    $space_cnt++;
                } else if ($counter <= $end) {
                    $calendarGrid .= $counter;
                    $counter++;
                } else {
                    $calendarGrid .= "&nbsp;";
                }
                $calendarGrid .= "</td>";
            }//for
            $calendarGrid .= "\n</tr>";
        }//while
        $this->rows = $calendarGrid . "\n";
    }

}
