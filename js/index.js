var xmlHttp = new XMLHttpRequest(),
  plakate = [],
  news = [],
  currentHour = 0,
  currentPlakat = 0,
  plakatTimeout = null,
  plakatTouch = new Hammer(document.getElementById('plakat'));

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
  document.getElementById("news").innerHTML = '';
  for (var i = news.length - 1; i >= 0; i--) {
    document.getElementById("news").innerHTML = '<div class="latestNews"><h3 class="newstitle">' + news[i].title +
      '</h3>' + (news[i].text!='' ? '<p class="newstext">' + news[i].text + '</p>' : '') + '</div>' + document.getElementById("news").innerHTML;
  }
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

function refreshRegenradar() {
  document.getElementById("regenradar").src = "http://www.wetteronline.de/?pid=p_radar_map&ireq=true&src=radar/vermarktung/p_radar_map_forecast/forecastLoop/NRW/latestForecastLoop.gif";
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
  var duration = 10;
  // once every cycle show a random cartoon.
  // if swiping through plakate, this wont happen
  if (index === plakate.length) {
    document.getElementById("plakat").src = 'http://www.ruthe.de/cartoons/strip_' + getRandom(1940) + '.jpg';
    currentPlakat = -1;
  } else {
    var plakat = plakate[index]; // plakat filename
    document.getElementById("plakat").src = 'displaycontent/plakate/' + plakat;
    duration = getDuration(plakat);
    console.log(index + '/' + (plakate.length-1) + ': ' + plakat);
  }

  plakatTimeout = window.setTimeout(function() {
    setPlakat(++currentPlakat);
  }, (duration * 1000));

  // returns the displaytime assigned to a plakat. defaults to 10 if none is found in the name
  function getDuration(filename){
    var duration = parseFloat(filename.split('-')[1]);
    if (isNaN(duration)) duration = 10;
    return duration;
  }
}

// register swipe gestures on plakateFrame
plakatTouch.on('swiperight', function(e) {
  if (--currentPlakat < 0) currentPlakat = plakate.length - 1;
  if (plakatTimeout) clearTimeout(plakatTimeout);
  setPlakat(currentPlakat);
});
plakatTouch.on('swipeleft', function(e) {
  if (++currentPlakat > plakate.length - 1) currentPlakat = 0;
  if (plakatTimeout) clearTimeout(plakatTimeout);
  setPlakat(currentPlakat);
});

function init() {
  currentHour = new Date().getHours();

  setPlakat(0);
  ladeNews();
  updateClock();
  ladeFahrplan();
  //refreshRegenradar();
}
