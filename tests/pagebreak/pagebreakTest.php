<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once('../CourseUp/PDExtension.php');

final class pagebreakTest extends TestCase
{
    public function testPageBreak(): void
    {
        $f = file_get_contents(__DIR__ . '/content.md');
        $p = PDExtension::instance()->text($f);
        
        $expected =
        
'<p>Page1</p>
<div class="pagebreak"></div>
<p>Page2</p>
<div class="pagebreak"></div>
<p>Page3</p>';

        $this->assertEquals($expected, $p);
    }
}