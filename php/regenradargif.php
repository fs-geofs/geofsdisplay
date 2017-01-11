<?php
  header ('Content-type:image/gif');
  include('GIFEncoder.class.php');
  $options = array('http'=>array('method'=>"GET", 'header'=>"User-Agent: geofsdisplay v1.0\r\n"));
  $context = stream_context_create($options);
  
  $frames=[];
  $framed=[];

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
    $datei = file_get_contents("http://www.wetteronline.de/?pid=p_radar_map&ireq=true&src=wmapsextract/vermarktung/prog2maps/short_range/$Y/$m/$d/NRW/grey_flat/$Y$m$d$H$i" . "_NRW.png", false, $context);
    $image = imagecreatefromstring($datei);
    
    ob_start();
    imagegif($image);
    $frames[]=ob_get_contents();
    $framed[]=($j==80?300:50);
    ob_end_clean();
  }
  
  $gif = new GIFEncoder($frames,$framed,0,2,0,0,0,'bin');
  echo $gif->GetAnimation();
?>