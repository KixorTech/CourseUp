<?
/*
This file is part of the CourseUp project.
http://courseup.org

(c) Micah Taylor
micah@kixortech.com

See http://courseup.org for license information.
*/

require_once('Parsedown.php');
require_once('ParsedownExtra.php');


class PDExtension extends ParsedownExtra
{
	function text($text)
	{
		global $publicErrorMessages;
		$markup = $text;

		$webRootPath = '';
		if(function_exists('getWebRoot'))
			$webRootPath = getWebRoot();

		//handle input keyword
		$maxInputRecurs = 4;
		for($depth=0; $depth<$maxInputRecurs; $depth++)
		{
			$inputRegex = '/\\\input\((.*)\)\n/';
			preg_match_all($inputRegex, $markup, $matches);

			$noInputCommands = count($matches[1]) < 1;
			if($noInputCommands)
				break;

			$splitParts = preg_split($inputRegex, $markup);
			$matchId = 0;
			$inputMarkup = '';
			for($matchId=0; $matchId<count($matches[1]); $matchId++)
			{
				$inputContent = '';
				$filePath = $matches[1][$matchId];
				$noLeadingSlash = substr($filePath, 0, 1) != '/';
				if($noLeadingSlash && function_exists('getRelativeDocRoot'))
					$filePath = getRelativeDocRoot() . $filePath;
				if(file_exists($filePath))
					$inputContent = file_get_contents($filePath);
				else //TODO log this
					if($publicErrorMessages) print '<b>Error:</b> Could not read "'.$filePath.'".<br>';
				$first = $splitParts[$matchId];
				$inputMarkup .= $first . $inputContent;
			}
			$lastPart = $splitParts[$matchId];
			$markup = $inputMarkup . $lastPart;
		}


		//get latex markers
		$markup = preg_replace('/\\\\\(/', '\\\\\\(', $markup);
		$markup = preg_replace('/\\\\\)/', '\\\\\\)', $markup);

		//replace webroot keyword
		$markup = preg_replace('/printWebRoot\(\)/', $webRootPath, $markup);

		//replace pagebreak keyword
		$pagebreakTags = '<div class="pagebreak"></div>';
		$markup = preg_replace('/\$pagebreak\$/', $pagebreakTags, $markup);

		//centering
		$beginCenteringTags = '<div class="beginCentering" style="text-align:center">';
		$endCenteringTags = '</div>';
		//$markup = preg_replace('/\$beginCentering\$/', $beginCenteringTags, $markup);
		//$markup = preg_replace('/\$endCentering\$/', $endCenteringTags, $markup);

		$presentRegex = '/\\\presentation\n/';
		$count = preg_match($presentRegex, $markup, $matches);
		if($count == 1) {
			//$presentStylePath = getRelativeDocRoot . "/include/present.css";
			$presentStyle = "<style>\n
#content {
position: absolute;
overflow: hidden;
margin: 0;
width: 100%;
height: 100%;
left: 0;
top:0;
font-size: 3vh;
}
li { margin-left: 0.2em; }
h3 {
font-size: 7vh;
margin: 0;
padding: 0.5em;
color: white;
background-color: black;
}
</style>\n";
			$presentScript = 
				"<script language=\"javascript\" src=\"" . $webRootPath . "/include/present.js\"></script>\n";
			$presentButton = "<button id=\"startPresentationButton\">&#9658;</button>\n";
			//$presentIncludes =  $presentStyle . $presentScript;
			$presentIncludes =  $presentButton . $presentScript;
		$markup = preg_replace($presentRegex, $presentIncludes, $markup);
		}

		//these should be replaced with login info in online mode
		//replace namebox keyword
		$boxStyle='padding-top:2em; display:inline-block; border-bottom:1px solid black; width:';
		$nameboxTags = 'Name: <span class="namebox" style="'.$boxStyle.'20em;"></span>';
		$markup = preg_replace('/\\\\namebox/', $nameboxTags, $markup);
		//replace mailbox keyword
		$mailboxTags = 'Box: <span class="mailbox" style="'.$boxStyle.'5em;"></span>';
		$markup = preg_replace('/\\\\mailbox/', $mailboxTags, $markup);
		//replace datebox keyword
		$dateboxTags = 'Date: <span class="datebox" style="'.$boxStyle.'8em;"></span>';
		$markup = preg_replace('/\\\\datebox/', $dateboxTags, $markup);

		//handle simple solution bounds
		$beginSolTag = '\beginSolution';
		$endSolTag = '\endSolution';
		if(isset($showSolution)) {
			$markup = str_replace($beginSolTag, '', $markup);
			$markup = str_replace($endSolTag, '', $markup);
		}
		else {
			while(strpos($markup, $beginSolTag) != FALSE) {
				$start = strpos($markup, $beginSolTag);
				$end = strpos($markup, $endSolTag, $start) + strlen($endSolTag);
				$markup = substr($markup, 0, $start-1) . substr($markup, $end);
			}
		}

		//handle solution bounds
		$beginSolTag = '\ifsolution';
		$elseSolTag = '\else';
		$endSolTag = '\fi';
		$beginSize = strlen($beginSolTag);
		$elseSize = strlen($elseSolTag);
		$endSize = strlen($endSolTag);

		while(strpos($markup, $beginSolTag) != FALSE) {
			$beginPos = strpos($markup, $beginSolTag);
			$elsePos = strpos($markup, $elseSolTag, $beginPos);
			$endPos = strpos($markup, $endSolTag, $beginPos);

			$trueString = substr($markup, $beginPos+$beginSize, $endPos-$beginPos+$beginSize);
			$falseString = '';
			if($elsePos != FALSE && $elsePos < $endPos) {
				$trueString = substr($markup, $beginPos+$beginSize, $elsePos-$beginPos+$beginSize);
				$falseString = substr($markup, $elsePos+$elseSize, $endPos-$elsePos+$elseSize);
			}

			$beforeCondition = substr($markup, 0, $beginPos);
			$afterCondition = substr($markup, $endPos+$endSize);
			$trueString = 'true';
			$falseString = 'false';
			if(isset($showSolution))
				$markup = $beforeCondition . $trueString . $afterCondition;
			else
				$markup = $beforeCondition . $falseString . $afterCondition;
		}


		//answer box
		/*
		$answerboxCD = '/\\\\answerbox\(\s*([0-9]*)\s*,\s*([0-9]*)\s*\)/';
		$count = preg_match($answerboxCD, $markup, $matches);
		if($count > 0) {
			$answerboxTag = '<div style="width:'.$matches[1].'; height:'.$matches[2].';"></div>';
			$markup = preg_replace($answerboxCD, $answerboxTag, $markup);
		}
		 */

		$markup = parent::text($markup);
		return $markup;
	}

	/*
	function __construct()
	{
		$this->BlockTypes['R'] []= 'RubricText'; 
	}

	protected function blockRubricText($Line,$Block)
	{
		if (preg_match('/^RUBRIC/', $Line['text'], $matches))
		{
			return array(
				'char' => $Line['text'][0],
				'element' => array(
					'name' => 'strong',
				),
				'text' => ''
			);
		}
	}

	protected function blockRubricTextContinue($Line,$Block)
	{
		if (isset($Block['complete']))
		{
			return;
		}

		// A blank newline has occurred
		if (isset($Block['interrupted']))
		{
			$Block['element']['text'] .= "\n";
			unset($Block['interrupted']);
		}

		//Check for end of the block. 
		if (preg_match('/\n\n/', $Line['text']))
		{
			$Block['element']['text'] = substr($Block['element']['text'], 1);
				$Block['complete'] = true;
			return $Block;
		}

		$Block['element']['text'] .= "\n".$Line['body'];

		return $block;
	}

	protected function blockBoldTextComplete($block)
	{
		return $block;
	}
	 */
}

?>

