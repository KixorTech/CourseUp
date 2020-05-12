<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once('../CourseUp/PDExtension.php');

final class solutionOnTest extends TestCase
{
    public function testSolutionOn(): void
    {
        $f = file_get_contents(__DIR__ . '/content.md');
        $p = PDExtension::instance()->text($f);

        $this->assertTrue(strpos($p, 'solution') !== false);
    }
}