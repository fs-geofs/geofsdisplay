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