<?php
/*
This file is part of the CourseUp project.
http://courseup.org

(c) Micah Taylor
micah@kixortech.com

See http://courseup.org for license information.
*/

#include '../CourseUp/Parsedown.php';
include '../CourseUp/ParsedownExtra.php';


class Extension extends ParsedownExtra
{
	function text($text)
	{
		$markup = parent::text($text);

		$markup = preg_replace('/\\(/', '\\\\(', $markup);
		$markup = preg_replace('/\\)/', '\\\\)', $markup);

		return $markup;
	}
}


$v = $argv[1];
$f = file_get_contents($v);
$p = Extension::instance()->text($f); 
echo $p;

?>

