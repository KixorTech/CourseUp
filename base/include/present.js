//window.onDOMContentLoaded = restructurePresentation();
//TODO figure out why the DOM load events fire without DOM being done
document.onload = setTimeout(setupPresentation, 20);

var currentSlide = 0;
var slides;

function setupPresentation()
{
	var b = document.getElementById('presentStartButton');
	b.onclick = startPresentation;
}

function handleTouch(e)
{
	if(!e) e=event;

	var w = window, d = document, m = d.documentElement, g = d.getElementsByTagName('body')[0];
	var cx = w.innerWidth || m.clientWidth || g.clientWidth;

	//var t = e.touches[0];
	var t = e.changedTouches[0];
	var tx = t.clientX;

	var horzPercent = tx / cx;
	//console.log(tx +'/'+ cx);
	//alert(tx +'/'+ cx +'='+horzPercent);
	var validRange = 0.20;
	if(horzPercent < validRange) {
		navPrev(e);
		e.preventDefault();
		return false;
	}
	if(horzPercent > 1-validRange) {
		navNext(e);
		e.preventDefault();
		return false;
	}
}

function handleKeys(e)
{
	if(!e) e=event;
	//console.log(e.key + ':' + e.keyCode + ' ' + e.code + ':'+ e.charCode);
	if(e.keyCode == 27) endPresentation();
	else if(e.keyCode == 116) startPresentation();
	else if(e.keyCode == 37) navPrev(e);
	else if(e.keyCode == 8) navPrev(e);
	else if(e.keyCode == 39) navNext(e);
	else if(e.keyCode == 32 || e.charCode == 32) navNext(e);
}

function slideCount()
{
	return slides.length - 2;
}

function navNext(e)
{
	currentSlide++;
	lastSlideId = slideCount();
	console.log(currentSlide + '/' + slideCount());
	if(currentSlide > lastSlideId)
	{
		endPresentation();
		currentSlide = lastSlideId + 1;
	}
	else
		updateSlide();
}

function navPrev(e)
{
	currentSlide--;
	firstSlideId = 0;
	console.log(currentSlide + '/' + slideCount());
	if(currentSlide < firstSlideId)
		currentSlide = firstSlideId;
	else
		updateSlide();
}

function updateSlide()
{
	var c = document.getElementById('content');
	c.innerHTML = slides[0] + slides[currentSlide+1];
}

function startPresentation()
{
	document.addEventListener("keydown", handleKeys);
	document.addEventListener("touchstart", handleTouch);

	var b = document.getElementsByTagName('head')[0];
	bi = b.innerText.replace('"stylesheet"', '"stylesheet alternate"');
	b.innerHTML = bi;

	var c = document.getElementById('content');
	var h = c.innerHTML;
	h = h.replace('"stylesheet alternate"', '"stylesheet"');
	slides = h.split("<hr>");
	//console.log(slides);
	if(currentSlide < 0 || currentSlide > slideCount())
		currentSlide = 0;
	updateSlide();
}

function endPresentation()
{
	document.removeEventListener("keydown", handleKeys);
	document.removeEventListener("touchstart", handleTouch);

	var b = document.getElementsByTagName('head')[0];
	bi = b.innerText.replace('"stylesheet alternate"', '"stylesheet"');
	b.innerHTML = bi;

	var c = document.getElementById('content');
	var n = '';
	//console.log(slides);
	for(var i=0; i<slides.length-1; i++)
		n += slides[i] + '<hr>';
	n += slides[slides.length-1];
	n = n.replace('"stylesheet"', '"stylesheet alternate"');
	//console.log(n);
	c.innerHTML = n;
	setupPresentation();
}

