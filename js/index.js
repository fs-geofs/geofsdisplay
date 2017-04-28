var xmlHttp = new XMLHttpRequest(),
  plakate = [],
  news = [],
  currentHour = 0,
  currentPlakat = 0,
  currentRegenradar = 0,
  regenradarTimeout = null,
  plakatTimeout = null,
  plakatTouchHandler = null;

// resets currently loaded news + plakate, rescans the directories, and loads all files
function reloadDisplaycontent() {
  /*plakate = [];
  news = [];
  xmlHttp.open('GET', 'php/plakate.php', true);
  xmlHttp.open('GET', 'php/news.php', true);
  ladeNews();*/
}

function addPlakate(filename) {
  plakate.push(filename);
}

function addNews(title, text) {
  news.push({title: title, text: text});
}

function ladeNews() {
  //auskommentiert um Regenradar nicht zu überschreiben
  //document.getElementById("news").innerHTML = '';
  news.forEach(function(element, index, array) {
    document.getElementById("news").innerHTML +=
      '<div class="latestNews"><h3 class="newstitle">' + element.title + '</h3>'
      + (element.text=='' ? '' : '<p class="newstext">' + element.text + '</p>') + '</div>';
  });
}

// update the clock
// every full hour refetch dispaycontent
function updateClock() {
  var time = new Date(),
    hours = leadingChar("&nbsp;", time.getHours()),
    minutes = leadingChar("0", time.getMinutes()),
    msecondsuntilrefresh = (60-time.getSeconds())*1000;

  if (time.getHours() != currentHour) {
    currentHour = time.getHours();
    reloadDisplaycontent();
  }

  document.getElementById("uhr").innerHTML = hours + ":" + minutes;
  window.setTimeout(updateClock, msecondsuntilrefresh);

  function leadingChar(char, int) {
    var result = int;
    if (int < 10) result = char + int;
    return result;
  }
}

function ladeFahrplan() {
  $.ajax({
    url: 'php/fahrplan.php',
    type: 'GET',
    success: function(responseText) {$('#fahrplan').html(responseText); },
    error: function(responseText) { console.error("could not get fahrplan.php"); }
  });
  window.setTimeout(ladeFahrplan, 15000);
}

function ladeMensa() {
  $.ajax({
    url: 'php/mensaplaene.php',
    type: 'GET',
    success: function(responseText) {$('#mensa').html(responseText); },
    error: function(responseText) { console.error("could not get mensaplaene.php"); }
  });
  window.setTimeout(ladeFahrplan, 15000);
}

function refreshRegenradar() {
  if (regenradarTimeout) clearTimeout(regenradarTimeout);
  // get new images from wetteronline.de via regenradar.php
  $.ajax({
    url: 'php/regenradar.php',
    type: 'GET',
    success: function(responseText) { document.getElementById('regenradar').innerHTML = responseText + '<img id="mapfooter">'; },
    error: function(responseText) { console.error("could not get regenradar.php"); }
  });
  // refresh every 5 minutes
  window.setTimeout(refreshRegenradar, 5*60*1000);
  // cycle through frames with 0.5 sec for each frame
  regenradarTimeout = window.setTimeout(cycleRegenradar, 500);
}

function refreshRegenradarGif() {
  // the script ignores the parameter, it's just there to force the browser to reload the file (bypassing all caches)
  document.getElementById('regenradar').innerHTML = '<img src="php/regenradargif.php?t=' + (new Date().getTime()) + '">';
  // refresh every 5 minutes
  window.setTimeout(refreshRegenradarGif, 5*60*1000);
}

function cycleRegenradar() {
  // hide old frame
  document.getElementById('regenradar').childNodes[currentRegenradar].classList.add('preload');
  // update count (it's always 23 frames)
  currentRegenradar = (currentRegenradar+1) % 23;
  // show new frame
  document.getElementById('regenradar').childNodes[currentRegenradar].classList.remove('preload');
  
  document.getElementById('mapfooter').src = document.getElementById('regenradar').childNodes[currentRegenradar].src;
  
  // schedule next cycle (show last frame a little bit longer)
  regenradarTimeout = window.setTimeout(cycleRegenradar, (currentRegenradar==22 ? 2000 : 500));
}

function refreshWetter() {
  // get new data
  $.ajax({
    url: 'php/wetter.php',
    type: 'GET',
    success: function(responseText) { document.getElementById('wetter').innerHTML = responseText; },
    error: function(responseText) { console.error("could not get wetter.php"); }
  });
  // refresh every 10 minutes
  window.setTimeout(refreshWetter, 10*60*1000);
}

//////////////////////////////////////////7

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
  
  var duration = 10;
  // once every cycle show a random cartoon.
  // if swiping through plakate, this wont happen
  if (index === plakate.length) {
    document.getElementById("plakat").src = 'http://ruthe.de/cartoons/strip_' + getRandom(2010) + '.jpg';
    currentPlakat = -1;
  } else {
    var plakat = plakate[index]; // plakat filename
    document.getElementById("plakat").src = 'displaycontent/plakate/' + plakat;
    duration = getDuration(plakat);
    console.log(index + '/' + (plakate.length-1) + ': ' + plakat);
  }

  plakatTimeout = window.setTimeout(nextPlakat, duration*1000);

  // returns the displaytime assigned to a plakat. defaults to 10 if none is found in the name
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
  if(currentPlakat == -1) currentPlakat = plakate.length-1;
  setPlakat(currentPlakat);
}

// register swipe gestures on plakateFrame
$(document).ready(function() {
  plakatTouchHandler = new Hammer(document.getElementById('plakat'));
  plakatTouchHandler.on('swiperight', prevPlakat);
  plakatTouchHandler.on('swipeleft',  nextPlakat);
});

function init() {
  currentHour = new Date().getHours();

  setPlakat(0);
  updateClock();
  ladeNews();
  ladeFahrplan();
  ladeMensa();
  //refreshRegenradar();
  refreshRegenradarGif();
  refreshWetter();
}

function EASTERbaconandEGGs()
{
  var iframe = document.createElement("iframe");
  iframe.src="https://geofs.uni-muenster.de/zeugs/bacon/";
  iframe.style="position:fixed; top:0; left:0; width:100vw; height:100vh; z-index:100;";
  iframe.id="bacon";
  document.body.appendChild(iframe);
  
  var countdown = document.createElement("div");
  countdown.innerHTML = '10';
  countdown.style="position:fixed; top:0; right:20px; font-size:20vh; color:white; z-index:101;";
  countdown.id="countdown";
  document.body.appendChild(countdown);
  
  for(var i=0; i<=10; i++)
    setTimeout("document.getElementById('countdown').innerHTML='" + (10-i) + "'", i*1000);
  setTimeout("document.getElementById('bacon').remove();", 10*1000+500);
  setTimeout("document.getElementById('countdown').remove();", 10*1000+500);
}

function EASTERzwiebelandGEEK()
{
  var iframe = document.createElement("iframe");
  iframe.src="https://geofs.uni-muenster.de/zeugs/zwiebel/";
  iframe.style="position:fixed; top:0; left:0; width:100vw; height:100vh; z-index:100;";
  iframe.id="zwiebel";
  document.body.appendChild(iframe);
  
  var countdown = document.createElement("div");
  countdown.innerHTML = '10';
  countdown.style="position:fixed; top:0; right:20px; font-size:20vh; color:white; z-index:101;";
  countdown.id="countdown";
  document.body.appendChild(countdown);
  
  for(var i=0; i<=10; i++)
    setTimeout("document.getElementById('countdown').innerHTML='" + (10-i) + "'", i*1000);
  setTimeout("document.getElementById('zwiebel').remove();", 10*1000+500);
  setTimeout("document.getElementById('countdown').remove();", 10*1000+500);
}