<?php
// <script type="text/javascript">

// the following function was reference from
// https://stackoverflow.com/questions/14643617/create-table-using-javascript
function tableCreate() {
    $dom = new DOMDocument();
	// $dom->loadHTML("index.php");

	// $body = $dom->getElementsByTagName('body')[0];

    // $dom = new DOMDocument();

	// echo $body->length;
	
	$tblDiv = $dom->createElement('div');
	// $tblDiv2 = $dom->createElement('div', 'these are words');

    $tblDiv->setAttribute('id', 'newCalendarDiv');

	// $tblDiv->appendChild($tblDiv2);
	
	// appendHTML($tblDiv, '<div>these are words</div>');

	// echo $tblDiv->saveHTML();

    // $tblDiv->innerHTML = "hi";

	// echo $tblDiv;

	$numWeeks = 5;
	$classDaysPerWeek = ['M','R','F'];
	$attributeNames = ['Homework Assignments', 'Labs', 'Readings'];
	

	
	$tbl = $dom->createElement('table');
	$tbl->setAttribute('style', 'width:100%;');
	$tbl->setAttribute('border', '1px solid #aaa;');
	$tbdy = $dom->createElement('tbody');

	$headerRow = $dom->createElement('tr'); // TODO: add header column
	$th = $dom->createElement('th');
	$th->textContent = "Week";
	$headerRow->appendChild($th);

	$th = $dom->createElement('th');
	$th->textContent = "Day";
	$headerRow->appendChild($th);

	for ($i = 0; $i < sizeof($attributeNames); $i++){
		$th = $dom->createElement('th');
		$th->textContent = $attributeNames[$i];
		$headerRow->appendChild($th);
	}
	$tbdy->appendChild($headerRow);
	


	for ($w = 0; $w < $numWeeks; $w++) {
		for ($d = 0; $d < sizeof($classDaysPerWeek); $d++) {
			$tr = $dom->createElement('tr');
			for ($c = 0; $c < sizeof($attributeNames)+2; $c++) {
				$td = $dom->createElement('td');
				$text = ''; // '\u0020' // adds a space?
				if ($c == 0 && $d == 0) {
					$text = "Week " . $w;
				}
				if ($c == 1) {
					$text = "Day " . ($d + $w*sizeof($classDaysPerWeek));
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