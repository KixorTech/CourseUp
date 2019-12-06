/*
This file is part of the CourseUp project.
http://courseup.org

(c) us beech
*/


$(document).ready(() => {
	$("#toggleCalendarFormat").click(() => {
		console.log("boi");
		$("#pastSessions").hide();
		$("#sessionToggleLabel").toggle();
	})

	$("#sessionToggleLabel").click(() => {
		$("#pastSessions").toggle();
	})
})