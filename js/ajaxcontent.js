function loadMensa() {
  $.ajax({
    url: 'php/mensaplaene.php',
    type: 'GET',
    success: function(responseText) { $('#mensa').html(responseText); },
    error: function(responseText) { console.error("could not get mensaplaene.php"); }
  });
  // not really a need to refresh (the display starts freshly every morning and the day's mensaplan is basically never changed during that day)
}

function refreshFahrplan() {
  $.ajax({
    url: 'php/fahrplan.php',
    type: 'GET',
    success: function(responseText) { $('#fahrplan').html(responseText); },
    error: function(responseText) { console.error("could not get fahrplan.php"); }
  });
  // refresh every 15 seconds
  window.setTimeout(refreshFahrplan, 15*1000);
}

function refreshWetter() {
  $.ajax({
    url: 'php/wetter.php',
    type: 'GET',
    success: function(responseText) { $('#wetter').html(responseText); },
    error: function(responseText) { console.error("could not get wetter.php"); }
  });
  // refresh every 5 minutes
  window.setTimeout(refreshWetter, 5*60*1000);
}

function refreshRegenradarGif() {
  // the script ignores the parameter, it's just there to force the browser to reload the file (bypassing all caches)
  document.getElementById('regenradar').innerHTML = '<img src="php/regenradargif.php?t=' + (new Date().getTime()) + '">';
  // refresh every 5 minutes
  window.setTimeout(refreshRegenradarGif, 5*60*1000);
}
