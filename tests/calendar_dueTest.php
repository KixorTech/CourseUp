<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require './Calendar.php';

final class calendar_dueTest extends TestCase
{
	// due_list is not populated when Calendar is configured
	// probably should happen at the same time

    //Calendar::getInstance()->parseCalendarFile('calendarTest.md');
    //$expected = '';
    //$actual = Calendar::getInstance()->getDueString();
    //$this->assertEquals($expected, $actual);
}