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

