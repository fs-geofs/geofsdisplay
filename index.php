<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<div id="container">
  <div id="content">
    <div id="header">
      <img src="images/geofspacman.png" id="geofspacman" class="fslogo" onclick="window.location.reload()">
      <img src="images/geoloek.png" id="geoloekbaum" class="fslogo" onclick="window.location.reload()">
      <div id="fahrplan"></div>
      <div id="uhr">hh:mm:ss</div>
    </div>
    
    <div id="main">
      <div id="news">
        <div class="latestNews">
          <h3 class="newstitle">Regenradar</h3>
          <p class="newstext" id="regenradar"></p>
          <p class="newstext" id="wetter"></p>
        </div>
      </div>
      <!-- <img id="regenradar" src="http://www.wetteronline.de/?pid=p_radar_map&ireq=true&src=radar/vermarktung/p_radar_map_forecast/forecastLoop/NRW/latestForecastLoop.gif"> -->
    </div>
    
    <div id="footer">
      <div id="praesiGeoloek" class="praesidienst">
        <h3>Pr&auml;senzdienste GeoL&ouml;k:</h3>
        <?php include("php/praesidienste-geoloek.php"); ?>
      </div>
      <div id="praesiGeofs" class="praesidienst">
        <h3 ondblclick='EASTERbaconandEGGs()'>Pr&auml;senzdienste GI:</h3>
        <?php include("php/praesidienste-geofs.php"); ?>
      </div>
    </div>
  
  </div>
  <div id="plakate">
    <img id="plakat" class="plakate" src="images/geofspacman.png" draggable="false" onmousedown="if (event.preventDefault) event.preventDefault()"></img>
  </div>
</div>

  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script type="text/javascript" src="./js/hammerjs-v2.0.6.min.js"></script>
  <script type="text/javascript" src="./js/index.js"></script>

  <?php
    include("php/plakate.php");
    include("php/mensaplaene.php");
    include("php/news.php");
  ?>

  <script type="text/javascript">
    init();
  </script>
</body>
</html>
