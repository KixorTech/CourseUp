<?
/*
This file is part of the CourseUp project.
http://courseup.org

(c) Micah Taylor
micah@kixortech.com

See http://courseup.org for license information.
*/

require_once('../PDExtension.php');
include('../enablePublicErrors.php');

$none =
"
Testing no input  

Done with none
\n";


$single =
"
Testing input  

\input(input.txt)

Done with one
\n";


$nested =
"
Testing input  

\input(input.txt)

Done with one

\input(inputNested.txt)

Done with two
\n";


$first =
"\input(input.txt)

Done with first
\n";


$last =
"
Testing last

\input(input.txt)\n";


$recurs =
"
Testing recurse  

\input(inputRecurs.txt)

Done with recurse
\n";



$pd = new PDExtension();
print '<h2>Input none</h2>';
$p = $pd->text($none); print $p;

print '<h2>Input single</h2>';
$p = $pd->text($single); print $p;

print '<h2>Input nested</h2>';
$p = $pd->text($nested); print $p;

print '<h2>Input first</h2>';
$p = $pd->text($first); print $p;

print '<h2>Input last</h2>';
$p = $pd->text($last); print $p;

print '<h2>Input recurs</h2>';
$p = $pd->text($recurs); print $p;

?>
