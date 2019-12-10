/*
This file is part of the CourseUp project.
http://courseup.org

(c) us beech
*/
let numWeeks = 5;
let numDaysAWeek = 3;

// the following function was reference from
// https://stackoverflow.com/questions/14643617/create-table-using-javascript
function tableCreate() {
	var body = document.getElementsByTagName('body')[0];

	var tblDiv = document.createElement('div');
	tblDiv.setAttribute('id', 'newCalendarDiv');

	var tbl = document.createElement('table');
	tbl.style.width = '100%';
	tbl.setAttribute('border', '1');
	var tbdy = document.createElement('tbody');
	for (var i = 0; i < numWeeks * numDaysAWeek; i++) {
		var tr = document.createElement('tr');
		for (var j = 0; j < 6; j++) {
			// if (i == 2 && j == 1) {
			// 	break
			// } else {
			var td = document.createElement('td');
			td.appendChild(document.createTextNode('\u0020')) // adds a space?
			// i == 1 && j == 1 ? td.setAttribute('rowSpan', '2') : null;
			tr.appendChild(td)
			// }
		}
		tbdy.appendChild(tr);
	}
	tbl.appendChild(tbdy);
	tblDiv.append(tbl);
	body.appendChild(tblDiv);
}

$(document).ready(() => {
	tableCreate();

	$("#newCalendarDiv").hide();

	$("#toggleCalendarFormat").click(() => {
		console.log("boi");
		$("#pastSessions").hide();
		$("#sessionToggleLabel").toggle();
		$('#newCalendarDiv').toggle();
	});

	$("#sessionToggleLabel").click(() => {
		$('#newCalendarDiv').hide();
		$("#pastSessions").toggle();
	})
})