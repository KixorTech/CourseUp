<?php
// <script type="text/javascript">
function tableCreate() {
    $dom = new DOMDocument();
	// $dom->loadHTML("index.php");

	// $body = $dom->getElementsByTagName('body')[0];

    // $dom = new DOMDocument();

	// echo $body->length;
	
	$tblDiv = $dom->createElement('div', 'these are words');
	$tblDiv2 = $dom->createElement('div', 'these are words');

    $tblDiv->setAttribute('id', 'newCalendarDiv2');

	$dom->appendChild($tblDiv);
	$tblDiv->appendChild($tblDiv2);

	echo $dom->saveHTML();
	
	// appendHTML($tblDiv, '<div>these are words</div>');

	// echo $tblDiv->saveHTML();

    // $tblDiv->innerHTML = "hi";

	// echo $tblDiv;

	// html = "<div "
	// tbl.style.width = '100%';
	// tbl.setAttribute('border', '1');
	// var tbdy = document.createElement('tbody');
	// for (var w = 0; w < numWeeks; w++) {
	// 	for (var d = 0; d < numDaysAWeek; d++) {
	// 		var tr = document.createElement('tr');
	// 		for (var c = 0; c < 6; c++) {
	// 			// if (i == 2 && j == 1) {
	// 			// 	break
	// 			// } else {
	// 			var td = document.createElement('td');
	// 			let text = '' // '\u0020' // adds a space?
	// 			if (c == 0 && d == 0) {
	// 				text = "Week " + w;
	// 			}
	// 			if (c == 1) {
	// 				text = "Day " + (d + w*numDaysAWeek);
	// 			}

	// 			td.innerHTML = text;
	// 			// i == 1 && j == 1 ? td.setAttribute('rowSpan', '2') : null;
	// 			tr.appendChild(td);
	// 			// }
	// 		}
	// 		tbdy.appendChild(tr);
	// 	}
	// }
	// tbl.appendChild(tbdy);
	// $tblDiv.append(tbl);
	// $body->appendChild($tblDiv);
	// $dom->saveHTML();
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