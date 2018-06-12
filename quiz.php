<?
/*
This file is part of the CourseUp project.
http://courseup.org

(c) Micah Taylor
micah@kixortech.com

See http://courseup.org for license information.
*/

$quizCounter = 0;

function createQuizTable()
{
	$db = DB::getDB();
	if( !$db->tableExists('quiz') )
	{
		$q = "CREATE TABLE quiz(
			id INT NOT NULL AUTO_INCREMENT UNSIGNED,
			quiz_id INT NOT NULL,
			quiz_uri VARCHAR(200),
			username VARCHAR(30),
			value TEXT,
			last_change DATETIME,
			PRIMARY KEY(id)
		)";
		$db->query($q);
	}
}



function getQuizLink()
{
	global $quizCounter;
	$uri = $_SERVER['REQUEST_URI'];
	$questionLink = $uri. '#quiz' .$quizCounter;
	$t = '';
	$t .= '<a href="' .$questionLink. '"></a>';
	return $t;
}

function getQuizMark()
{
	$t = '&#x2731;';
	return $t;
}

function printQuizItem($formField)
{
	global $quizCounter;
	$t = '';
	$t .= getQuizLink();
	$t .= getQuizMark();
	$t .= $formField;
	$quizCounter += 1;
	print $t;
}

function quizText($text, $points)
{
	global $quizCounter;
	$t = $text . '<br />';
	$t .= '<input type="text" points="' .$points. '" class="quizInput" id="quiz' .$quizCounter. '"></input>';
	printQuizItem($t);
}

function quizTextarea($text, $rows=3, $cols=40)
{
	$x = $cols;
	$y = $rows;
	global $quizCounter;
	$t = $text . '<br />';
	$t .= '<textarea rows="' .$y. '" cols="' .$x. '" class="quizInput" id="quiz' .$quizCounter. '"></textarea>';
	printQuizItem($t);
}

function quizSelect()
{
	global $quizCounter;
	$n = func_num_args();
	$args = func_get_args();

	if($n < 1)
		return;

	$t = $args[0] .'<br />';
	$t .= '<select class="quizInput" id="quiz' .$quizCounter. '">';
	for($i=0; $i<$n; $i++)
		$t .= '<option value="v' . $i . '">' .$args[$i]. '</option>';
	$t .= '</select>';

	printQuizItem($t);
}

function quizRadio()
{
	global $quizCounter;
	$n = func_num_args();
	$args = func_get_args();

	if($n < 1)
		return;

	$t = $args[0] .'<br />';
	for($i=1; $i<$n; $i++)
		$t .= '<label><input type="radio" class="quizInput" name="quiz' .$quizCounter. '" id="quiz' .$quizCounter.'_'.$i. '"  value="v' . $i . '">' .$args[$i]. '</input></label><br />';

	printQuizItem($t);
}

?>
