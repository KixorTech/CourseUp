/*
This file is part of the CourseUp project.
http://courseup.org

(c) Micah Taylor
micah@kixortech.com

See http://courseup.org for license information.
*/

function timer(ts)
{
	var now = new Date();
	var due = new Date(ts);
	var remain_ms = due.getTime() - now.getTime();

	if(remain_ms < 0) {
		//console.log(remain);
		//return;
	}

	var remain = new Date(remain_ms);
	var r = remain_ms / 1000;
	var m = r % 60;
	r = (r - m) / 60;
	var h = r % 24;
	r = (r-h) / 24;
	var d = r;

	console.log(ts +'_'+ remain_ms/1000 +' '+ d +':'+ h +':'+  m);
}
