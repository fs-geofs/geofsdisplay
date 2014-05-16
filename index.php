<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script type="text/javascript">
	var plakate = [];
	var cartoons = [];
	var newsTitle = [];
	var newsText = [];
	var praesiCounter = 0;
	var newsCounter = 0;
	var currentHour = 0;
	xmlHttp = new XMLHttpRequest();

	function resetFileNames(type){
		if (type == "plakte") {
			plakte = [];
		}
		if (type == "news"){
			var newsTitle = [];
			var newsText = [];
		}
	}

	function addFileNames(ziel, name){
		if (ziel == "plakate"){
			plakate.push(name);
		}
		if (ziel == "cartoons"){
			cartoons.push(name);
		}
		console.log(name);
	}

	function addNews(filepath, text){
		var filearray = filepath.split('.');
		newsTitle.push(filearray[0]);
		newsText.push(text);
	}

	function ladeNews(){
		// Für durchlaufende News:
			// newsCounter++;
			// var index = newsCounter%newsText.length;
			// document.getElementById("news").innerHTML = '<div class="latestNews" style="display: none;"><div class="newstitle">' + newsTitle[index] + '</div><div class="newstext">' + newsText[index] + '</div></div>' + document.getElementById("news").innerHTML;
			// $(".latestNews").fadeIn("slow");
			// console.log("counter: " + index);
			// window.setTimeout("ladeNews()", 10000);
		//Für dauerhaft angezeigte News:
			for (var i = newsTitle.length - 1; i >= 0; i--) {
				document.getElementById("news").innerHTML = '<div class="latestNews"><div class="newstitle">' + newsTitle[i] + '</div><div class="newstext">' + newsText[i] + '</div></div>' + document.getElementById("news").innerHTML;
			};
	}

	function aktiviereUhr(){
		var time = new Date();
		if (time.getHours() != currentHour) {
			currentHour = time.getHours();
			resetFileNames("plakate");
			resetFileNames("news");
			xmlHttp.open('GET', 'php/plakate.php', true);
			xmlHttp.open('GET', 'php/news.php', true);
			ladeNews();
		};
		var hours = leadingChar("&nbsp;", time.getHours());
		var minutes = leadingChar("0", time.getMinutes());
		document.getElementById("uhr").innerHTML = hours + ":" + minutes;
		var msecondsuntilrefresh = (60-time.getSeconds())*1000;
		window.setTimeout("aktiviereUhr()", msecondsuntilrefresh);
	}

	function leadingChar(char, int){
		var result = int;
		if (int < 10) {
			result = char + int;
		};
		return result;
	}
	
	function ladeFahrplan(){
		$.ajax({
		    url: 'php/fahrplan.php',
		    type: 'GET',
		    success: function(responseText){
		        $('#fahrplan').html(responseText);
		    },
		    error: function(responseText){
		    	console.log("errorLoading");
		    }
		});
		window.setTimeout("ladeFahrplan()", 15000);
	}

	function viewPraesiDay(){
		// $(".col1").hide();
		// $(".col2").hide();
		// $(".col3").hide();
		// $(".col4").hide();
		// $(".col5").hide();
		// var today = new Date();
		// // var day = (praesiCounter%5)+1;
		// var day = today.getDay();
		// $(".col" + day).fadeIn();
		// praesiCounter++;
		// window.setTimeout("viewPraesiDay()", 5000);
	}

	function getTime(name){
		var nameArray = name.split('-');
		var time = parseFloat(nameArray[1]);

		if (isNaN(time)) {time = 10};

		return time;
	}

	function refreshRegenradar(){
		document.getElementById("regenradar").src = "http://www.wetteronline.de/?pid=p_radar_map&ireq=true&src=radar/vermarktung/p_radar_map_forecast/forecastLoop/NRW/latestForecastLoop.gif";
		window.setTimeout("viewPraesiDay()", 200000);
	}
</script>
<link rel="stylesheet" type="text/css" href="css/style.css">
<?php
	include("php/plakate.php");
	include("php/cartoons.php");
	include("php/news.php");
?>
</head>
<body> 
<div id="content">
	<img src="images/geofspacman.png" style="position: absolute; right: 0;">
	<div id="uhr">hh:mm:ss</div>
	<img src="images/geoloek.png" style="position: absolute; left: 0;">
	<div id="news">
		<div></div>
	</div>
	<div id="fahrplan">
	</div>
	<!-- <img id="regenradar" src="http://www.wetteronline.de/?pid=p_radar_map&ireq=true&src=radar/vermarktung/p_radar_map_forecast/forecastLoop/NRW/latestForecastLoop.gif"> -->
	<div id="praesiGeoloek" class="praesidienst">
		Pr&auml;senzdienste GeoL&ouml;k:
		<?php include("php/praesidienste-geoloek.php"); ?>
	</div>
	<div id="praesiGeofs" class="praesidienst">
		Pr&auml;senzdienste GI:
		<?php include("php/praesidienste-geofs.php"); ?>
	</div>
</div>
<div id="plakateFrame">
<img id="plakat1" class="plakate" src="images/geofspacman.png"></img>
<!-- <img id="plakat0" class="plakate" src="images/geofspacman.png"></img> -->
</div>
<script type="text/javascript">
	var timer = 5000;
	var i = 0;
	var cartoonCounter = 0;
	var showCartoon = false;

	viewPraesiDay();

	function changePoster(url){
		document.getElementById("plakat1").src = 'displaycontent/plakate/' + url;
		// var img1 = "plakat" + i%2;
		// var img2 = "plakat" + (1 - i%2);
		// document.getElementById(img1).src = 'images/plakate/' + url;
		// $("#"+img1).fadeIn( 1000);
		// $("#"+img2).fadeOut( 1000);
	}

	function getRandom(max){
		var rnd = Math.floor(Math.random()*max)+1;
		console.log(rnd);
		if (rnd < 10) {
			rnd = "000" + rnd;
		}
		else if (rnd < 100) {
			rnd = "00" + rnd;
		}
		else if (rnd < 1000) {
			rnd = "0" + rnd;
		}
		return rnd;
	}

	function setImage(){
		if (showCartoon){			
			document.getElementById("plakat1").src = 'http://www.ruthe.de/cartoons/strip_' + getRandom(1733) + '.jpg';
			//document.getElementById("plakat1").src = 'images/cartoons/' + cartoons[cartoonCounter%cartoons.length];
			//cartoonCounter++;
			timer = 10;
			showCartoon = false;
		}
		else {	
			changePoster(plakate[i%plakate.length]);
			console.log(i + ': ' + plakate[i%plakate.length]);
			timer = getTime(plakate[i%plakate.length]);
			i++;
			if (i%plakate.length == 0) {
				showCartoon = true;
			};
		}
		console.log("plakatTimer: " + timer + "sek.")
		window.setTimeout("setImage()", (timer * 1000));
	}

	setImage();
	ladeNews();
	aktiviereUhr();
	ladeFahrplan();
	//refreshRegenradar();
</script>
</body>
