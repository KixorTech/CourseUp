<?php

namespace eu\freeplace\php\calendar;

include_once './CalandarTableView.php';
include_once './Holidays.php';
include_once './CalendarCalculations.php';

/**
 * @author Elmar Dott
 */
class Calendar {

    private $months = array("Januar", "Februar", "M?rz", "April", "Mai", "Juni",
        "Juli", "August", "September", "Oktober", "November", "Dezember");
    private $days = array("Mo", "Di", "Mi", "Do", "Fr", "Sa", "So");
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

        echo $view->drawGrid();
    }

    private function setMonthName($month) {
        return $this->months[$month - 1];
    }

}
