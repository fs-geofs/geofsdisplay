function addPlakat(filename) {
  plakate.push(filename);
}

function addNews(title, text) {
  news.push({title: title, text: text});
}

function showNews() {
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

function showPlakate() {
  setPlakat(0);
}