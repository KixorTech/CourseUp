//window.onDOMContentLoaded = restructurePresentation();
document.onload = waitRestructure();
document.addEventListener("keypress", handleKeys, false);

var currentSlide = 0;
var slides;
function waitRestructure() {
	//TODO figure out why the DOM load events fire without DOM being done
	setTimeout(setupPresentation, 20);
}

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
	if(e.keyCode == 116) startPresentation();
	if(e.keyCode == 37) navPrev(e);
	if(e.keyCode == 39) navNext(e);
	if(e.charCode == 32) navNext(e);
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

