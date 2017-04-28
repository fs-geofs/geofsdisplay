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