function loadMensa() {
  $.ajax({
    url: 'php/mensaplaene.php',
    type: 'GET',
    success: function(responseText) { $('#mensa').html(responseText); },
    error: function(responseText) { console.error("could not get mensaplaene.php"); }
  });
  // not really a need to refresh (the display starts freshly every morning and the day's mensaplan is basically never changed during that day)
}

function loadVLAufzeichnung() {
  $.ajax({
    // current URL (may be/will quite likely be subject to change)
    url: 'https://electures.uni-muenster.de/electures/kraken/public_schedule/GEO1.json',
    // permanent URL (currently redirects to above URL but doesn't send CORS headers, so not directly useable)
    // url: 'https://electures.uni-muenster.de/room/GEO1.json',
    type: 'GET',
    success: function(responseText) {
      var text =
        responseText.data
        .filter(
          (e) => new Date(e.attributes.start) - new Date() < 4*7*24*60*60*1000
        )
        .map(
          (e) => e.attributes.lecturers.join(', ') + ': ' +
                 e.attributes.title    + ' (' +
                 (new Date(e.attributes.start)).toLocaleString()     + ' &ndash; ' +
                 (new Date(e.attributes.end))  .toLocaleTimeString() + ')'
        )
        .join('<br>');
        
      $('#vlaufzeichnung').html('In den nächsten 4 Wochen sind ' + (text ? 'folgende' : 'keine') + ' Vorlesungsaufzeichnungen im GEO1-Hörsaal geplant' + (text ? ':<br>'+text : '.'));
    },
    error: function(responseText) { console.error("could not get VL-Aufzeichnungen from electures.uni-muenster.de"); $('#vlaufzeichnung').html("Fehler beim Laden der Daten von electures.uni-muenster.de"); }
  });
  // not really a need to refresh (the display starts freshly every morning and lectures are not scheduled to be captured the day they are held)
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

// currently replaced by GIF method, but keep for potential later use
/*
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
}*/
