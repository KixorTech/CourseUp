<?php

namespace eu\freeplace\php\calendar;

include_once './CalandarTableView.php';
include_once './Holidays.php';
include_once './CalendarCalculations.php';

/**
 * @author Elmar Dott
 * 
 * @editor me beech, Olivia
 */
class Calendar {

    private $months = array("January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December");
    private $days = array("Mon", "Tues", "Wed", "Thurs", "Fri", "Sat", "Sun");
    private $daysCountOfMonth = array("31", "28", "31", "30", "31", "30", "31",
        "31", "30", "31", "30", "31");
    private $holidays;
    private $weekday;
    private $day;
    private $month;
    private $year;

    public function __construct() {
        $date = getDate();
        $this->day = $date['mday'];
        $this->month = $date['mon'];
        $this->year = $date['year'];
        $this->weekday = date("w");

        $holidays = new Holidays($this->year);
        $this->holidays = $holidays->getHolidays();

        $model = new CalendarCalculations();
        if ($model->isLeapYear($this->year)) {
            $this->daysCountOfMonth[1] = 29;
        }
    }

    public function draw() {

        $model = new CalendarCalculations();
        $start = $model->startDay($this->day, $this->weekday);
        $end = $this->daysCountOfMonth[($this->month - 1)];

        $view = new CalandarTableView("Kalender");
        $view->header($this->days, $this->setMonthName($this->month));
        $view->setCurrentDate($this->day, $this->month, $this->year);
        $view->setHolidays($this->holidays);
        $view->calculateRows($start, $end); //start, end
        echo "<div id=\"calendarActual\">";
        echo $view->drawGrid();
        echo "</div>";
    }

    private function setMonthName($month) {
        return $this->months[$month - 1];
    }

}
