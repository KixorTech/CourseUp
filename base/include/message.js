
var longPressTimer;

function attachListener()
{
	var m = document.getElementsByTagName("html")[0];
	//m.ondblclick = newBoxHandler;
	//m.addEventListener('dblclick', newBoxHandler, false);
	m.onmousedown = startLongPress;
	m.ontouchstart = startLongPress;

	m.onmousemove = cancelLongPress;
	m.onmouseup = cancelLongPress;
	m.ontouchmove = cancelLongPress;
	m.ontouchend = cancelLongPress;
}

function cancelLongPress(e)
{ clearTimeout(longPressTimer); }

function startLongPress(e)
{ longPressTimer = setTimeout( function(){ newBoxHandler(e); }, 500); }

function newBoxHandler(e)
{
	e.preventDefault();
	if(e.stopPropagation)
		e.stopPropagation();
	if(window.event)
	{ e=window.event; e.cancelBubble = true; }

	//get mouse pos
	var x;
	var y;
	if(e.touches && e.touches[0]) {
		x = e.touches[0].clientX;
		y = e.touches[0].clientY;
	}
	else {
		x = e.clientX;
		y = e.clientY;
	}

	x += window.pageXOffset;
	y += window.pageYOffset;

	//get scroll pos

	//make div
	var d = document.createElement('div');
	d.setAttribute('class',  'textBox');
	d.style.left = x+'px';
	d.style.top = y+'px';
	d.style.position = 'absolute';
	d.style.zindex = 1;
	//d.innerHTML = '<div class="boxHeader">T M L</div><textarea cols="20" rows="4"></textarea>';
	//left arrow &#x21A9; word bubble &#x1F4AC; note &#x1F4DD; plus &#x2795;
	var header = '';
	header += '<div class="boxHeader" onmousedown="startDrag(event, this.parentNode)">';
	header += '<span title="Text" class="hoverGlow">&#x1F524;</span>';
	header += ' <span title="Media" class="hoverGlow">&#x1F3A5;</span>';
	header += ' <span title="Link" class="hoverGlow">&#x1F517;</span>';
	header += '<span style="float: right;">';
	header += '<span title="Reply" class="hoverGlow">&#x1F4AC;</span>';
	header += ' <span title="Hide" class="hoverGlow">&#x1F53A;</span>';
	header += '</span></div><textarea cols="20" rows="4"></textarea>';
	d.innerHTML = header;

	//add div
	var m = document.getElementsByTagName("html")[0];
	m.appendChild(d);

	return false;
}

var b = document.getElementsByTagName("body")[0];
//b.addEventListener("load", attachListener, true); //doesn't work in firefox?
b.onload=attachListener;
