var plakate = [],
  news = [],
  currentHour = 0,
  currentPlakat = 0,
  plakatTimeout = null,
  plakatTouchHandler = null;

// updates display content that changes rarely (rescans the plakate and news directories and refreshes mensaplan)
function reloadDisplayContent() {
  loadPlakate();
  loadNews();
  loadMensa();
}

function updateClock() {
  // update the clock
  var time = new Date(),
    hours = leadingChar("&nbsp;", time.getHours()),
    minutes = leadingChar("0", time.getMinutes()),
    msecondsuntilrefresh = (60-time.getSeconds())*1000;
    
  document.getElementById("uhr").innerHTML = hours + ":" + minutes;
  window.setTimeout(updateClock, msecondsuntilrefresh);

  // every full hour reload rarely changing display content
  if (time.getHours() != currentHour) {
    currentHour = time.getHours();
    reloadDisplayContent();
  }

  // helper
  function leadingChar(char, int) {
    var result = int;
    if (int < 10) result = char + int;
    return result;
  }
}

function init() {
  currentHour = new Date().getHours();
  updateClock();
  reloadDisplayContent();
  refreshFahrplan();
  refreshWetter();
  refreshRegenradarGif();
}