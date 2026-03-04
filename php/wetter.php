<?php
  $url = "https://www.uni-muenster.de/BAI/data/weather2.txt";
   
  if (! $input = @file_get_contents($url))
  {
    $wetter = "Konnte Wetterdaten nicht laden";
  }
  else
  { 
    preg_match('~<table class="news_weather" summary="Aktuelles Wetter">.*Lokalzeit</th> <td>(\d\d:\d\d)</td>.*Temperatur</th> <td>(-?\d?\d\.?\d?).*Luftfeuchte</th> <td>(\d\d0?).*Beaufort</span>: (\d\d?).*Windrichtung</th> <td>aus (\w\w?\w?).*Luftdruck</th> <td>(\d\d\d\d?\.?\d?).*</table>~si', $input, $wetterdaten);
    
    $uhrzeit      = $wetterdaten[1];
    $temperatur   = $wetterdaten[2];
    $luftfeuchte  = $wetterdaten[3];
    $windstaerke  = $wetterdaten[4];
    $windrichtung = $wetterdaten[5];
    $luftdruck    = $wetterdaten[6];        
  }
  
  echo"<strong>Aktuelles Wetter vom Dach des GEO1</strong> (Stand $uhrzeit Uhr)<br />Temperatur: <strong>$temperatur&nbsp;°C</strong> &nbsp;&bull;&nbsp; Luftfeuchte: <strong>$luftfeuchte&nbsp;%</strong> &nbsp;&bull;&nbsp; Wind: <strong>$windstaerke&nbsp;bft aus $windrichtung</strong>";
?>