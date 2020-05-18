<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require './Calendar.php';

final class calendar_sessionTest extends TestCase
{
    Calendar::getInstance()->parseCalendarFile('calendarTest.md');
    $expected = 'Array ( [1] => * Sample Test due 2020-04-09.15:00 [2] => * Nothing to do [3] => * what? due +1 [4] => * last session doesn\'t show up on schedule page * nothing due +1.15:27 [5] => * see? i am hidden. also this .md breaks table view ) 1';
    $actual = Calendar::getInstance()->getCalendarString();
    $this->assertEquals($expected, $actual);
}