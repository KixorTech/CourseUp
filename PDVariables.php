<?php
/*
This file is part of the CourseUp project.
http://courseup.org

(c) Cambron Johnson, Isaac Austin
johnsocp@rose-hulman.edu
austinin@rose-hulman.edu

See http://courseup.org for license information.
*/

class PDVariables
{
	private $vars = array();

	function __construct()
	{
		$vars = array();
	}

	// private function declareVar($key, $value)
	// {
	// 	if (!array_key_exists($key, $vars))
	// 	{
	// 		$vars[$key] = $value;
	// 	}
	// }

	private function setVar($key, $value)
	{
		// if (array_key_exists($key, $vars))
		// {
			$vars[$key] = $value;
		// }
	}

	private function getVar($key)
	{
		return $vars[$key];
	}

	public function handleVars($markdown) 
	{
		$matches = array();
		preg_match_all('/[_]\w*[_=]\w*/', $markdown, $matches, PREG_SET_ORDER);
		print_r($matches);

		// set variables found by preg_match
		foreach ($matches as $value) {
			$pieces = explode('=', $value[0]);
			setVar($pieces[0], $pieces[1]);
		}

		// replace variables in $markdown
		// foreach ($vars as $varname => $varvalue) {
		// 	preg_replace($varname, $varvalue, $markdown);
		// }

		return $markdown;
	}
}
?>