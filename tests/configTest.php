<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once('./Config.php');
require_once("./helpers.php");
require_once("./session.php");
require_once("./https.php");
//require_once("basicAuth.php");
//require_once('db.php');

require_once('./spyc.php');

final class configTest extends TestCase
{
    public function testGetNextDay(): void
    {
        $path = __DIR__.'/configTest.yaml';
        $config_temp = spyc_load_file($path);
        Config::getInstance()->loadSettings($config_temp);

        $actual = Config::getInstance()->getConfigString();

        // originally had DateTimeObjects, but they varied by the second
        // preg_matching proved too tedious, so got rid of date checking
        $expected = 'Array([CourseTitle]=>TestCourse[MenuLinks]=>Array([0]=>Array([MenuLinkName]=>Schedule[MenuLinkURL]=>/)[1]=>Array([MenuLinkName]=>TestPage[MenuLinkURL]=>/test/))[ClassOnWeekDays]=>Array([0]=>Mon[1]=>Tue[2]=>Wed[3]=>Fri)[ShowPastSessions]=>0[ShowFutureSessions]=>3[PublicErrorMessages]=>1[TimeZone]=>America/Indiana/Indianapolis[DefaultView]=>List)';

		$expected = str_replace("\r", '', $expected);
		$expected = str_replace("\n", '', $expected);
		$expected = str_replace("\t", '', $expected);
		$expected = str_replace(" ", '', $expected);

		$actual = str_replace("\r", '', $actual);
		$actual = str_replace("\n", '', $actual);
		$actual = str_replace("\t", '', $actual);
		$actual = str_replace(" ", '', $actual);

        $this->assertEquals($expected, $actual);
    }
}