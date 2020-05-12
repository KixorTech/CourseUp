<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once('../CourseUp/PDExtension.php');

final class submitterFormTest extends TestCase
{
    public function testSolutionOff(): void
    {
        $f = file_get_contents(__DIR__ . '/content.md');
        $p = PDExtension::instance()->text($f);

        $expected = 
        '<p>Name Name: <span class="namebox" style="padding-top:2em; display:inline-block; border-bottom:1px solid black; width:20em;"></span></p>
<p>Mail Box: <span class="mailbox" style="padding-top:2em; display:inline-block; border-bottom:1px solid black; width:5em;"></span></p>
<p>Date Date: <span class="datebox" style="padding-top:2em; display:inline-block; border-bottom:1px solid black; width:8em;"></span></p>';
        $this->assertEquals($expected, $p);
    }
}