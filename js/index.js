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
  showNews();*/
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

function init() {
  currentHour = new Date().getHours();
  updateClock();
  showNews();
  showPlakate();
  ladeMensa();
  refreshFahrplan();
  refreshWetter();
  refreshRegenradarGif();
}