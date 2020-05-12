<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once('../CourseUp/PDExtension.php');

final class contentmdTest extends TestCase
{
    public function testContentmd(): void
    {
        $f = file_get_contents(__DIR__ . '/content.md');
        $p = PDExtension::instance()->text($f);

        $expected = '<h2>Content.md</h2>
<p>This is a content.md file</p>';
        $this->assertEquals($expected, $p);
    }
}