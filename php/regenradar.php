<?php
  // wetteronline.de provides regenradar forecast for the next 90 minutes
  // include prev 30 mins to illustrate trend, stop at 80 mins just in case the image is not on their server yet
  for($j=-30; $j<=80; $j+=5)
  {
    // timestamp $j minutes into the past/future
    $stamp = strtotime("$j minutes");
    // extract date() values for easy use in URL assembling
    $Y = date('Y', $stamp);
    $m = date('m', $stamp);
    $d = date('d', $stamp);
    $H = date('H', $stamp);
    $i = date('i', $stamp);
    // round minutes down to 5 min intervals
    $i = floor($i / 5) * 5;
    // and still have a leading zero
    if($i<10) $i = "0$i";

    // add images to page
    echo "<img class='preload' src='http://www.wetteronline.de/?pid=p_radar_map&ireq=true&src=wmapsextract/vermarktung/prog2maps/short_range/$Y/$m/$d/NRW/grey_flat/$Y$m$d$H$i" . "_NRW.png'>";
    
    // provides images up to NOW (but not into the future) - keep it for reference just in case whatever
    //echo "http://www.wetteronline.de/?ireq=true&pid=p_radar_map&src=wmapsextract/vermarktung/global2maps/$Y/$m/$d/NRW/grey_flat/$Y$m$d$H$i" . "_NRW.png<br>";
  }
?>