<?php
  $url = "https://www.uni-muenster.de/Klima/";
   
  if (! $input = @file_get_contents($url))
  {
    $wetter = "Konnte Wetterdaten nicht laden";
  }
  else
  { 
    preg_match('~<table class="news_weather" summary="Aktuelles Wetter">.*Temperatur</th> <td>(\d\.?\d?).*Luftfeuchte</th> <td>(\d\d).*Beaufort</span>: (\d\d?).*Windrichtung</th> <td>aus (\w\w?\w?).*Luftdruck</th> <td>(\d\d\d\d?\.?\d?).*</table>~si', $input, $wetterdaten);
    
    $temperatur   = $wetterdaten[1];
    $luftfeuchte  = $wetterdaten[2];
    $windstaerke  = $wetterdaten[3];
    $windrichtung = $wetterdaten[4];
    $luftdruck    = $wetterdaten[5];        
  }
  
  echo"Temperatur: <strong>$temperatur&nbsp;°C</strong><br>Luftfeuchte: <strong>$luftfeuchte&nbsp;%</strong><br>Wind: <strong>$windstaerke&nbsp;bft aus $windrichtung</strong>";
?>