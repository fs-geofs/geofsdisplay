function showEasterEgg(url) {
  var iframe = document.createElement("iframe");
  iframe.src = url;
  iframe.style = "position:fixed; top:0; left:0; width:100vw; height:100vh; z-index:100;";
  iframe.id = "eastereggoverlay";
  document.body.appendChild(iframe);
  
  var countdown = document.createElement("div");
  countdown.innerHTML = "10";
  countdown.style="position:fixed; top:0; right:20px; font-size:20vh; color:white; z-index:101;";
  countdown.id="countdown";
  document.body.appendChild(countdown);
  
  for(var i=0; i<=10; i++) {
    setTimeout("document.getElementById('countdown').innerHTML='" + (10-i) + "'", i*1000);
  }
  setTimeout("document.getElementById('eastereggoverlay').remove();", 10*1000+500);
  setTimeout("document.getElementById('countdown').remove();", 10*1000+500);
}

function EASTERbaconandEGGs() {
  showEasterEgg("https://geofs.uni-muenster.de/zeugs/bacon/");
}

function EASTERzwiebelandGEEK() {
  showEasterEgg("https://geofs.uni-muenster.de/zeugs/zwiebel/");
}

function hockenpong() {
  showEasterEgg("https://hockenpong.de/");
}