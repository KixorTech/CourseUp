<?php
/*
This file is part of the CourseUp project.
http://courseup.org

(c) Micah Taylor
micah@kixortech.com

See http://courseup.org for license information.
*/

require_once('../PDExtension.php');
include('../enablePublicErrors.php');

$text =
"
*Italic* and **bold** text goes here
- Item
	1. Item 1
	1. Item 2
	1. Item 3\n";

$span = '<span>'.$text."</span>\n";
$div = '<div>'.$text."</div>\n";
$mdiv = '<div markdown="1">'.$text."</div>\n";

$pd = new Parsedown();
print '<h2>ParseDown</h2>';
$p = $pd->text($text); print $p;
$p = $pd->text($span); print $p;
$p = $pd->text($div); print $p;
$p = $pd->text($mdiv); print $p;

$pd = new ParsedownExtra();
print '<h2>ParseDownExtra</h2>';
$p = $pd->text($text); print $p;
$p = $pd->text($span); print $p;
$p = $pd->text($div); print $p;
$p = $pd->text($mdiv); print $p;

$pd = new PDExtension();
print '<h2>PDExtension</h2>';
$p = $pd->text($text); print $p;
$p = $pd->text($span); print $p;
$p = $pd->text($div); print $p;
$p = $pd->text($mdiv); print $p;

?>
