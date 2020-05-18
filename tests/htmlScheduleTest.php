<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require './htmlSchedule.php';

final class htmlScheduleTest extends TestCase
{
    public function testGetNextDay(): void
    {
        $tz = new DateTimeZone('America/Indiana/Indianapolis');
        $day = DateTime::createFromFormat('Y-m-d', '2016-03-07', $tz);
        $day2 = DateTime::createFromFormat('Y-m-d', '2016-03-08', $tz);
        $day = getNextDay($day);
        $this->assertEquals($day, $day2);
    }
}