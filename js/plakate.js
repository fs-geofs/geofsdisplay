// returns a random int as string with leading zeros (for ruthe comic IDs)
function getRandom(max) {
  var rnd = Math.floor(Math.random()*max)+1;
  if (rnd < 10) rnd = "000" + rnd;
  else if (rnd < 100) rnd = "00" + rnd;
  else if (rnd < 1000) rnd = "0" + rnd;
  return rnd;
}

function setPlakat(index) {
  if (plakatTimeout) clearTimeout(plakatTimeout);
  
  // at the end of every cycle show a random cartoon
  var url, duration;
  if (index === plakate.length) {
    url = 'http://ruthe.de/cartoons/strip_' + getRandom(2036) + '.jpg';
    duration = 5;
    currentPlakat = -1;
  } else {
    // otherwise use filename from plakate array
    url = 'displaycontent/plakate/' + plakate[index];
    duration = getDuration(plakate[index]);
  }
  
  document.getElementById("plakat").src = url;
  
  // display bar that goes across the screen in approx. the time the plakat is supposed to be displayed
  var balken = document.getElementById("plakatbalken");
  balken.style.transition = "";
  balken.style.width = "0";
  // this has to happen a little later to actually cause rendering
  setTimeout(function(){
    balken.style.transition = "width " + (duration-0.5) + "s linear";
    balken.style.width = "100%";
  }, 100);

  // schedule next plakat
  plakatTimeout = window.setTimeout(nextPlakat, duration*1000);

  // returns the duration assigned to a plakat via the filename. defaults to 10 if none is found in the name.
  function getDuration(filename){
    var duration = parseFloat(filename.split('-')[1]);
    if (isNaN(duration)) duration = 10;
    return duration;
  }
}

function nextPlakat() {
  currentPlakat++;
  setPlakat(currentPlakat);
}

function prevPlakat() {
  currentPlakat--;
  if(currentPlakat <= -1) currentPlakat = plakate.length-1;  // currentPlakat might be as low as -2 (happens when swiping back while on cartoon)
  setPlakat(currentPlakat);
}

// register swipe gestures on <img id="plakat"> element
$(document).ready(function() {
  plakatTouchHandler = new Hammer(document.getElementById('plakat'));
  plakatTouchHandler.on('swiperight', prevPlakat);
  plakatTouchHandler.on('swipeleft',  nextPlakat);
});