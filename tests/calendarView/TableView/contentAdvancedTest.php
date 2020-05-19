<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once('../CourseUp/Config.php');
require_once('../CourseUp/Calendar.php');
require_once('../CourseUp/TableView.php');

require_once("../CourseUp/session.php");
require_once("../CourseUp/https.php");

require_once('../CourseUp/spyc.php');

final class contentAdvancedTest extends TestCase
{
    public function testContentAdvancedmd(): void
    {
        $path = __DIR__.'/viewTest.yaml';
        $Spyc  = new Spyc;
        $config_temp = $Spyc->loadFile($path);
        Config::getInstance()->loadSettings($config_temp);

        $f = file_get_contents(__DIR__ . '/contentAdvanced.md');
        $cal = Calendar::getInstance()->parseCalendarFile($f);

        $tv = new TableView();
        $actual = $tv->parseCalendar();

        $expected = file_get_contents(__DIR__ . '/contentAdvanced.html');
        $this->assertFalse(strpos($actual, $expected) !== false);
    }
}