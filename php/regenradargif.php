<?php
  // GIF code example: http://www.jeroenvanwissen.nl/weblog/php/howto-generate-animated-gif-with-php
  header ('Content-type:image/gif');
  // class from here: http://www.phpclasses.org/browse/package/3163.html
  // deep download link: http://www.phpclasses.org/browse/download/zip/package/3163/name/gifmerge-2007-09-25.zip
  include('GIFEncoder.class.php');
  
  // add user agent to be fair
  // http://stackoverflow.com/questions/2107759/php-file-get-contents-and-headers
  $options = array('http'=>array('method'=>"GET", 'header'=>"User-Agent: geofsdisplay v1.0\r\n"));
  $context = stream_context_create($options);
  
  // get current data from wetteronline.de API endpoint
  $data = json_decode(file_get_contents('https://tiles.wo-cloud.com/metadata?lg=wr&period=periodCurrentLowRes&type=period', false, $context));
  
  // load marker icon for Münster once
  $markerImage = null;
  $markerData = @file_get_contents('https://radar.wo-cloud.com/desktop/assets/shared/position-marker/positionMarker_mDPI.png', false, $context);
  if ($markerData !== false)
  {
    $markerImage = imagecreatefromstring($markerData);
    if ($markerImage !== false)
    {
      imagesavealpha($markerImage, true);
    }
  }
  
  $frames=[];
  $dauer=[];

  foreach($data->timesteps as $step)
  {
    // build tiles parameter. this is how Christoph found the necessary content for this parameter:
    //   1. open browser console -> go to https://www.wetteronline.de/regenradar/nordrhein-westfalen -> observe network traffic
    //   2. look at images starting with "composite" -> select the one that has Münster in it (look for NRW border around Osnabrück)
    //   3. copy `tiles` parameter -> base64_decode (function `atob` in JavaScript) -> you get this long string starting with "topo|"
    //   4. replace variables in path as seen below -> remove "_cities_excluded" from "rr_geooverlay" so that city names are INcluded
    //   -> that's it!
    // note that "ZL" stands for "zoom level" and that (66,42) and (132,84) correspond to Münster's tile numbers (when rounded) in the
    // "slippy tile names" scheme (https://wiki.openstreetmap.org/wiki/Slippy_map_tilenames) in zoom level 7 and 8 respectively.
    $tiles = 'seamask|1;;0;0|geo/prozess/karten/produktkarten/wetterradar/generate/topoTiles/bw_landseamask/v2/ZL8/512/132_84.png$' .
             'topo|1;;0;0|geo/prozess/karten/produktkarten/wetterradar/generate/topoTiles/rr_topography/v3/ZL8/512/132_84.jpg$' .
             'r|2;;0;0;false|' . $step->layers->europe->rain->ptypPath . $step->layers->europe->rain->path . '/' . implode('/', $step->layers->europe->rain->timePath) . '/ZL7/522/sprite/66_42.png;' .
             $step->layers->global->rain->ptypPath . $step->layers->global->rain->path . '/' . implode('/', $step->layers->global->rain->timePath) . '/ZL7/522/border/66_42.png';
    // build URL (parameters `k` and `time` left out as they don't seem to be necessary, "rr" probably stands for "rain radar")
    $url = 'https://tiles.wo-cloud.com/composite?format=png&lg=rr&tiles=' . urlencode(base64_encode($tiles));

    // retrieve PNG from wetteronline.de API
    $datei = file_get_contents($url);
    // Turn the data into an image
    $image = imagecreatefromstring($datei);
    
    // Crop away Düsseldorf and Köln at the bottom so that Münster is vertically centered
    $cropped = imagecrop($image, ['x' => 0, 'y' => 0, 'width' => 512, 'height' => 365]);
    
    $markerX = 320;
    $markerY = 150;
    $markerTextY = 140;
    $markerTextX = $markerX;
    if ($markerImage !== null)
    {
      imagecopy($cropped, $markerImage, $markerX, $markerY, 0, 0, imagesx($markerImage), imagesy($markerImage));
      $markerTextY = $markerY + imagesy($markerImage) + 5;
      $markerTextX = $markerX + (imagesx($markerImage) / 2) - ((imagefontwidth(5) * strlen('Muenster')) / 2);
    }
    
    // Add the frame's timestamp as text below the "Münster" label
    // 1. Extract the timestamp
    $timezoneoffset = $data->liveid[14];   // `liveid` has format "20210714-1640-2" -- I *assume* the last bit is the timezone
    $hour = ($step->layers->europe->rain->timePath[3] + $timezoneoffset) % 24;   // `timePath` has format ["2021","07","14","16","40","v11"]
    $time = ($hour < 10 ? '0' : '') . $hour . ':' . $step->layers->europe->rain->timePath[4];   // leading zero, hour, colon, minute
    // 2. Choose a text color
    $textcolor = imagecolorallocate($cropped, 0, 0, 0);   // nice and simple black
    imagestring($cropped, 5, (int)$markerTextX, (int)$markerTextY, 'Muenster', $textcolor);
    // 3. Write onto the image (5 uses the biggest font size, (342,178) puts it under the "Münster" label, use (450,345) for bottom right corner)
    imagestring($cropped, 5, 342, 178, $time, $textcolor);
    
    // save frame
    ob_start();
    imagegif($cropped);   // convert to GIF
    $frames[]=ob_get_contents();
    $dauer[]=100;
    ob_end_clean();
  }
  
  // add last frame once more for double the time so that it is displayed 3x longer and thus aids dinstinguishing the end of the loop from the other frames
  $frames[] = $frames[count($frames)-1];
  $dauer[] = 200;
  
  // Build GIF
  $gif = new GIFEncoder($frames,$dauer,0,2,0,0,0,'bin');
  // put it out there
  echo $gif->GetAnimation();
?>