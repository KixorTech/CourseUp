<?php

namespace eu\freeplace\php\calendar;

/**
 * @author Elmar Dott
 */
class CalendarCalculations {

    public function __construct() {

    }

    public function isLeapYear($year) {
        $date = ($year >= 2000) ? $year : (($year < 80) ? $year + 2000 : $year + 1900);
        $chk01 = $date % 4;
        $chk02 = $date % 100;
        $chk03 = $date % 400;

        return (($chk03 == 0) ? (true) : (($chk02 == 0) ? (false) : (($chk01 == 0) ? (true) : (false))));
    }

    public function calculateEastern($year) {
        // Algorithm of Gauss for Karfreitag
        $a = $year % 19;
        $b = $year % 4;
        $c = $year % 7;

        $m = floor(8 * floor($year / 100) + 13) / 25 - 2;
        $s = floor($year / 100) - floor($year / 400) - 2;
        $M = ( 15 + $s - $m ) % 30;
        $N = ( 6 + $s ) % 7;
        $d = ( 19 * $a + $M ) % 30;

        if ($d == 29) {
            $D = 28;
        } elseif ($d == 28 && $a > 10) {
            $D = 27;
        } else {
            $D = $d;
        }

        $e = ( 2 * $b + 4 * $c + 6 * $D + $N ) % 7;
        $OE = $D + $e + 1;

        if ($OE <= 12) {
            return (21 + $OE) . "#3";
        } else {
            return ($OE - 12) . "#4";
        }
    }

    public function startDay($day, $weekday) {
        $var1 = $day % 7;
        $var2 = $weekday;
        $start = "";

        if ($var2 == 1) {
            switch ($var1) {
                case 1: $start = 1;
                    break;
                case 0: $start = 2;
                    break;
                case 6: $start = 3;
                    break;
                case 5: $start = 4;
                    break;
                case 4: $start = 5;
                    break;
                case 3: $start = 6;
                    break;
                case 2: $start = 7;
                    break;
            }
        } else if ($var2 == 2) {
            switch ($var1) {
                case 2: $start = 1;
                    break;
                case 1: $start = 2;
                    break;
                case 0: $start = 3;
                    break;
                case 6: $start = 4;
                    break;
                case 5: $start = 5;
                    break;
                case 4: $start = 6;
                    break;
                case 3: $start = 7;
                    break;
            }
        } else if ($var2 == 3) {
            switch ($var1) {
                case 3: $start = 1;
                    break;
                case 2: $start = 2;
                    break;
                case 1: $start = 3;
                    break;
                case 0: $start = 4;
                    break;
                case 6: $start = 5;
                    break;
                case 5: $start = 6;
                    break;
                case 4: $start = 7;
                    break;
            }
        } else if ($var2 == 4) {
            switch ($var1) {
                case 4: $start = 1;
                    break;
                case 3: $start = 2;
                    break;
                case 2: $start = 3;
                    break;
                case 1: $start = 4;
                    break;
                case 0: $start = 5;
                    break;
                case 6: $start = 6;
                    break;
                case 5: $start = 7;
                    break;
            }
        } else if ($var2 == 5) {
            switch ($var1) {
                case 5: $start = 1;
                    break;
                case 4: $start = 2;
                    break;
                case 3: $start = 3;
                    break;
                case 2: $start = 4;
                    break;
                case 1: $start = 5;
                    break;
                case 0: $start = 6;
                    break;
                case 6: $start = 7;
                    break;
            }
        } else if ($var2 == 6) {
            switch ($var1) {
                case 6: $start = 1;
                    break;
                case 5: $start = 2;
                    break;
                case 4: $start = 3;
                    break;
                case 3: $start = 4;
                    break;
                case 2: $start = 5;
                    break;
                case 1: $start = 6;
                    break;
                case 0: $start = 7;
                    break;
            }
        } else if ($var2 == 0) {
            switch ($var1) {
                case 0: $start = 1;
                    break;
                case 6: $start = 2;
                    break;
                case 5: $start = 3;
                    break;
                case 4: $start = 4;
                    break;
                case 3: $start = 5;
                    break;
                case 2: $start = 6;
                    break;
                case 1: $start = 7;
                    break;
            }
        } else {
            $start = (7 - $var2) + $var1;
        }
        return $start;
    }

}
