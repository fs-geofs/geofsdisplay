<?php
  // GIF code example: http://www.jeroenvanwissen.nl/weblog/php/howto-generate-animated-gif-with-php
  header ('Content-type:image/gif');
  // class from here: http://www.phpclasses.org/browse/package/3163.html
  // deep download link: http://www.phpclasses.org/browse/download/zip/package/3163/name/gifmerge-2007-09-25.zip
  include('GIFEncoder.class.php');
  
  // wetteronline.de refuses to respond when no user agent is transmitted -> let's trick them
  // http://stackoverflow.com/questions/2107759/php-file-get-contents-and-headers
  $options = array('http'=>array('method'=>"GET", 'header'=>"User-Agent: geofsdisplay v1.0\r\n"));
  $context = stream_context_create($options);
  
  // wetteronline.de needs timestamps in UTC
  date_default_timezone_set("UTC");
  
  $frames=[];
  $dauer=[];

  // wetteronline.de provides regenradar forecast for the next 90 minutes
  // include prev 30 mins to illustrate trend, stop at 80 mins just in case later images aren't on their server yet
  for($j=-30; $j<=75; $j+=15)
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

    // retrieve PNG from wetteronline.de """API"""
    $datei = file_get_contents("http://www.wetteronline.de/?pid=p_radar_map&ireq=true&src=wmapsextract/vermarktung/prog2maps/short_range/$Y/$m/$d/NRW/grey_flat/$Y$m$d$H$i" . "_NRW.png", false, $context);
    // Turn the data into an image
    $image = imagecreatefromstring($datei);
    
    // Crop the area around Münster, allowing space at the bottom to later add the footer
    $cropped = imagecrop($image, ['x' => 0, 'y' => 0, 'width' => 520, 'height' => 365]);
    // Extract the map's footer that holds the wetteronline logo and, most importantly, the frames' timestamp
    $footer = imagecrop($image, ['x' => 0, 'y' => 550, 'width' => 520, 'height' => 21]);
    // Copy the footer onto the cropped map
    imagecopy($cropped, $footer, 0, 344, 0, 0, 520, 21);
    
    // save frame
    ob_start();
    imagegif($cropped);   // convert to GIF
    $frames[]=ob_get_contents();
    $dauer[]=($j==75?300:100);   // show last frame a bit longer to aid dinstinguishing the end of the loop from the other frames
    ob_end_clean();
  }
  
  // Build GIF
  $gif = new GIFEncoder($frames,$dauer,0,2,0,0,0,'bin');
  // put it out there
  echo $gif->GetAnimation();
?>