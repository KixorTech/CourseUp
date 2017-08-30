<?
#require_once('Parsedown.php');
require_once('ParsedownExtra.php');


class PDExtension extends ParsedownExtra
{
	function text($text)
	{
		$markup = $text;

		$markup = preg_replace('/\\\\\(/', '\\\\\\(', $markup);
		$markup = preg_replace('/\\\\\)/', '\\\\\\)', $markup);

		//replace webroot keyword
		$webRootPath = getWebRoot();
		$markup = preg_replace('/printWebRoot\(\)/', $webRootPath, $markup);

		//replace pagebreak keyword
		$pagebreakTags = '<div class="pagebreak"></div>';
		$markup = preg_replace('/\$pagebreak\$/', $pagebreakTags, $markup);

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

