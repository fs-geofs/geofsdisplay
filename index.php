<!doctype html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
  <div id="content">
    <img src="images/geofspacman.png" style="position: absolute; right: 0;" onclick="window.location.reload()">
    <div id="uhr">hh:mm:ss</div>
    <img src="images/geoloek.png" style="position: absolute; left: 0;" onclick="window.location.reload()">
    <div id="news"><div></div></div>
    <div id="fahrplan"></div>
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
  <img id="plakat" class="plakate" src="images/geofspacman.png" draggable="false" onmousedown="if (event.preventDefault) event.preventDefault()"></img>

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
