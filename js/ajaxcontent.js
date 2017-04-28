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