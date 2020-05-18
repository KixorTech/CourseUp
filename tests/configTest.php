<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once './Config.php';

final class configTest extends TestCase
{
    public function testGetNextDay(): void
    {
        $path = getFileRoot().'/tests/configTest.yaml';
        $config_temp = spyc_load_file($path);
        Config::getInstance()->loadSettings($config_temp);
        $parsers = Config::getInstance()->buildParserArray();

        $actual = Config::getInstance()->getConfigString();
        print($actual);

        // the datetime constantly updates with current time. need regex?
        $expected = 'Array ( [CourseTitle] => Test Course [MenuLinks] => Array ( [0] => Array ( [MenuLinkName] => Schedule [MenuLinkURL] => / ) [1] => Array ( [MenuLinkName] => Test Page [MenuLinkURL] => /test/ ) ) [FirstQuarterDay] => DateTime Object ( [date] => 2016-04-07 \d\d:\d\d:\d\d.000000 [timezone_type] => 3 [timezone] => America/Indiana/Indianapolis ) [LastBeforeBreak] => DateTime Object ( [date] => 2020-05-01 \d\d:\d\d:\d\d.000000 [timezone_type] => 3 [timezone] => America/Indiana/Indianapolis ) [FirstAfterBreak] => DateTime Object ( [date] => 2020-05-18 \d\d:\d\d:\d\d.000000 [timezone_type] => 3 [timezone] => America/Indiana/Indianapolis ) [LastQuarterDay] => DateTime Object ( [date] => 2020-06-14 \d\d:\d\d:\d\d.000000 [timezone_type] => 3 [timezone] => America/Indiana/Indianapolis ) [ClassOnWeekDays] => Array ( [0] => Mon [1] => Tue [2] => Wed [3] => Fri ) [ShowPastSessions] => 0 [ShowFutureSessions] => 3 [PublicErrorMessages] => 1 [TimeZone] => America/Indiana/Indianapolis [DefaultView] => List ) 1';

        $this->assertEquals(preg_match($expected, $actual), 1);
    }
}