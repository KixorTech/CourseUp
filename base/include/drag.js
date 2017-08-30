var offsetX;
var offsetY;
var scrollX;
var scrollY;
var obj;
var moving = false;
var drag = true;


function startDrag(e, ob)
{
	if(!drag)
		return;

	var ev = e? e : window.event;
	obj = ob;


	if(document.all)
	{
		ev.cancelBubble=true;
		ev.returnValue = false;
	}
	else
	{
		ev.preventDefault();
		ev.stopPropagation();
	}

	obj.style.left = obj.offsetLeft+"px";
	obj.style.top = obj.offsetTop+"px";

	offsetX = ev.clientX - parseInt(obj.style.left);
	offsetY = ev.clientY - parseInt(obj.style.top);

	scrollX = window.pageXOffset;
	scrollY = window.pageYOffset;

	moving = true;

	document.onmouseup=stopDrag;
	document.onmousemove=move;

   return false;
}

function move(e)
{
	if(moving)
	{
		var ev = e? e : window.event;

		var newX = (ev.clientX - offsetX);
		var newY = (ev.clientY - offsetY);
		var diffY = window.pageYOffset - scrollY;
		var diffX = window.pageXOffset - scrollX;

		if(diffY != 0) newY += diffY;
		if(diffX != 0) newX += diffX;

		obj.style.left = newX + "px";
		obj.style.top = newY + "px";

	}
}


function stopDrag(e)
{

	var ev = e? e : window.event;

	moving = false;
	document.onmouseup=null;
	document.onmousemove=null;

	if(document.all)
	{
		ev.cancelBubble=false;
		ev.returnValue=false;
	}
	else
	{
		ev.preventDefault();
		ev.stopPropagation();
	}
}



