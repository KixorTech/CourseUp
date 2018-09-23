<?php
/*
This file is part of the CourseUp project.
http://courseup.org

(c) Micah Taylor
micah@kixortech.com

See http://courseup.org for license information.
*/

include '../CourseUp/PDExtension.php';


$v = $argv[1];
$f = file_get_contents($v);
$p = PDExtension::instance()->text($f); 
echo $p;

?>

