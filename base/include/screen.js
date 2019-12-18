/*
This file is part of the CourseUp project.
http://courseup.org

(c) Olivia Penry and Tyson Clark
*/

$(document).ready(() => {
	$("#newCalendarDiv").hide();

	$("#toggleCalendarFormat").click(() => {
		$("#pastSessions").hide();
		// $("#currentSessions").toggle();
		$("#sessionToggleLabel").toggle();
		$('#newCalendarDiv').toggle();
	});

	$("#sessionToggleLabel").click(() => {
		$('#newCalendarDiv').hide();
		$("#pastSessions").toggle();
	})
})