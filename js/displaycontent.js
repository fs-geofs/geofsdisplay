function addPlakat(filename) {
  plakate.push(filename);
}

function addNews(title, text) {
  news.push({title: title, text: text});
}

function loadPlakate() {
  $.ajax({
    url: 'php/plakate.php',
    type: 'GET',
    success: function(responseText) { plakate = JSON.parse(responseText); showPlakate(); },
    error: function(responseText) { console.error("could not get plakate"); }
  });
}

function loadNews() {
  $.ajax({
    url: 'php/news.php',
    type: 'GET',
    success: function(responseText) { news = JSON.parse(responseText); showNews(); },
    error: function(responseText) { console.error("could not get news"); }
  });
}

function showPlakate() {
  setPlakat(0);
}

function showNews() {
  // remove all news that are not regenradar, weather or mensa (i.e. everything after mensa)
  while((e = document.getElementById('mensa').parentElement.nextSibling) != null) e.remove();
  // add news from global object
  news.forEach(function(newsbeitrag) {
    document.getElementById("news").innerHTML +=
      '<div class="latestNews">' +
        '<h3 class="newstitle">' +
          newsbeitrag.title +
        '</h3>' +
        (newsbeitrag.text=='' ? '' : '<p class="newstext">' + newsbeitrag.text + '</p>') +
      '</div>';
  });
}