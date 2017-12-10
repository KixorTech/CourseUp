<?
#require_once('Parsedown.php');
require_once('ParsedownExtra.php');


class PDExtension extends ParsedownExtra
{
	function text($text)
	{
		$markup = $text;

		//get latex markers
		$markup = preg_replace('/\\\\\(/', '\\\\\\(', $markup);
		$markup = preg_replace('/\\\\\)/', '\\\\\\)', $markup);

		//replace webroot keyword
		$webRootPath = getWebRoot();
		$markup = preg_replace('/printWebRoot\(\)/', $webRootPath, $markup);

		//replace pagebreak keyword
		$pagebreakTags = '<div class="pagebreak"></div>';
		$markup = preg_replace('/\$pagebreak\$/', $pagebreakTags, $markup);

		//centering
		$beginCenteringTags = '<div class="beginCentering" style="text-align:center">';
		$endCenteringTags = '</div>';
		//$markup = preg_replace('/\$beginCentering\$/', $beginCenteringTags, $markup);
		//$markup = preg_replace('/\$endCentering\$/', $endCenteringTags, $markup);

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

