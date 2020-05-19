<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once('../CourseUp/Calendar.php');
require_once('../CourseUp/Config.php');
require_once("../CourseUp/helpers.php");
require_once("../CourseUp/session.php");
require_once("../CourseUp/https.php");

require_once('../CourseUp/spyc.php');

final class calendar_sessionTest extends TestCase
{
	public function testCalendar()
	{
		// $path = file_get_contents(__DIR__.'/calendarTest.md');
		// $calendar = Calendar::getInstance();
		// $calendar->parseCalendarFile($path);

    	// $expected = 'Array ( [1] => * Sample Test due 2020-04-09.15:00 [2] => * Nothing to do [3] => * what? due +1 [4] => * last session doesn\'t show up on schedule page * nothing due +1.15:27 [5] => * see? i am hidden. also this .md breaks table view )';

		// $actual = $calendar->getCalendarString();
		
		// $expected = str_replace("\r", '', $expected);
		// $expected = str_replace("\n", '', $expected);
		// $expected = str_replace("\t", '', $expected);
		// $expected = str_replace(" ", '', $expected);

		// $actual = str_replace("\r", '', $actual);
		// $actual = str_replace("\n", '', $actual);
		// $actual = str_replace("\t", '', $actual);
		// $actual = str_replace(" ", '', $actual);

    	// $this->assertEquals($expected, $actual);
	}
}