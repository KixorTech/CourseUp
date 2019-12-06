<?php

namespace eu\freeplace\php\calendar;

include_once 'CalendarCalculations.php';

/**
 * @author Elmar Dott
 */
class Holidays {

    //[Month] [Day] [OPTION: 0 = "HolidayName" | 1 = Type]
    private $holidays;
    private $year;

    public function __construct($year) {
        $this->year = $year;
        $this->setStaticHolidays();
        $this->setDynamicHolidays();
    }

    public function getHolidays() {
        return $this->holidays;
    }

    private function setDynamicHolidays() {

        $model = new CalendarCalculations();

        $eastern = $model->calculateEastern($this->year);
        $tmp00 = explode("#", $eastern);
        $m = $tmp00[1];
        $d = $tmp00[0];
        $this->holidays[$m] [$d] [1] = 1;
        $this->holidays[$m] [$d] [0] = "Karfreitag";
    }

    private function setStaticHolidays() {
        //JAN
        $this->holidays[1] [1] [1] = 1;
        $this->holidays[1] [1] [0] = "Neujahr";
        $this->holidays[1] [6] [1] = 1;
        $this->holidays[1] [6] [0] = "Heiligen drei Könige";

        //Mai
        $this->holidays[5] [1] [1] = 1;
        $this->holidays[5] [1] [0] = "Maifeiertag - Tag der Arbeit";

        //AUG
        $this->holidays[8] [15] [1] = 1;
        $this->holidays[8] [15] [0] = "Maria Himmelfahrt";

        //OKT
        $this->holidays[10] [3] [1] = 1;
        $this->holidays[10] [3] [0] = "Tag der deutschen Einheit";

        //NOV
        $this->holidays[11] [1] [1] = 1;
        $this->holidays[11] [1] [0] = "Allerheiligen";

        //DEZ
        $this->holidays[12] [24] [1] = 2;
        $this->holidays[12] [24] [0] = "Heiligabend";
        $this->holidays[12] [25] [1] = 1;
        $this->holidays[12] [25] [0] = "1. Weihnachtsfeiertag";
        $this->holidays[12] [26] [1] = 1;
        $this->holidays[12] [26] [0] = "2. Weihnachtsfeiertag";
        $this->holidays[12] [31] [1] = 2;
        $this->holidays[12] [31] [0] = "Silvester";
    }

}
