<?php
// <script type="text/javascript">
function tableCreate() {
    $dom = new DOMDocument();
	// $dom->loadHTML("index.php");

	// $body = $dom->getElementsByTagName('body')[0];

    // $dom = new DOMDocument();

	// echo $body->length;
	
	$tblDiv = $dom->createElement('div');
	// $tblDiv2 = $dom->createElement('div', 'these are words');

    $tblDiv->setAttribute('id', 'newCalendarDiv2');

	// $tblDiv->appendChild($tblDiv2);
	
	// appendHTML($tblDiv, '<div>these are words</div>');

	// echo $tblDiv->saveHTML();

    // $tblDiv->innerHTML = "hi";

	// echo $tblDiv;

	$numWeeks = 5;
	$numDaysAWeek = 3;

	
	$tbl = $dom->createElement('table');
	$tbl->setAttribute('style', 'width:100%;');
	$tbl->setAttribute('border', '1px solid #aaa;');
	$tbdy = $dom->createElement('tbody');
	for ($w = 0; $w < $numWeeks; $w++) {
		for ($d = 0; $d < $numDaysAWeek; $d++) {
			$tr = $dom->createElement('tr');
			for ($c = 0; $c < 6; $c++) {
				$td = $dom->createElement('td');
				$text = ''; // '\u0020' // adds a space?
				if ($c == 0 && $d == 0) {
					$text = "Week " . $w;
				}
				if ($c == 1) {
					$text = "Day " . ($d + $w*$numDaysAWeek);
				}

				$td->textContent = $text;
				// i == 1 && j == 1 ? td.setAttribute('rowSpan', '2') : null;
				$tr->appendChild($td);
				// }
			}
			$tbdy->appendChild($tr);
		}
	}
	$tbl->appendChild($tbdy);
	$tblDiv->appendChild($tbl);
	// $body->appendChild($tblDiv);
	$dom->appendChild($tblDiv);
	
	echo $dom->saveHTML();
}


// function appendHTML(DOMNode $parent, $source) {
//     $tmpDoc = new DOMDocument();
//     $tmpDoc->loadHTML($source);
//     foreach ($tmpDoc->getElementsByTagName('body')->item(0)->childNodes as $node) {
//         $node = $parent->ownerDocument->importNode($node, true);
//         $parent->appendChild($node);
//     }
// }

// </script>
?>