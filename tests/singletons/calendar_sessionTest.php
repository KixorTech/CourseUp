<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once './Calendar.php';

final class calendar_sessionTest extends TestCase
{
	public function testCalendar()
	{
		$path = __DIR__.'/calendarTest.md';
		$calendar = Calendar::getInstance();
		$calendar->parseCalendarFile($path);

    	$expected = 'Array ( [1] => * Sample Test due 2020-04-09.15:00 [2] => * Nothing to do [3] => * what? due +1 [4] => * last session doesn\'t show up on schedule page * nothing due +1.15:27 [5] => * see? i am hidden. also this .md breaks table view )';

    	$actual = $calendar->getCalendarString();

    	$this->assertEquals($expected, $actual);
	}
}