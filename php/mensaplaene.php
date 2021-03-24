<?php
  $url = "https://bburke.gitlab.io/mymensa2openmensa/feed/ring.xml";
  /* 
    Available data:
    
    mensa_aasee.xml
    mensa_am_ring.xml
    mensa_bispinghof.xml
    mensa_da_vinci.xml
    mensa_steinfurt.xml
    bistro_coerdehof.xml
    bistro_denkpause.xml
    bistro_durchblick.xml
    bistro_frieden.xml
    bistro_huefferstift.xml
    bistro_kabu.xml
    bistro_katho.xml
    bistro_oeconomicum.xml
    
    "Die Dateien werden Mo. - Sa. von 06:01 bis 16:01 Uhr alle 5 Minuten aktualisiert."
    
    The Studentenwerk outsourced this to digital signage company infomax.de
  */
   
  if (! $input = @file_get_contents($url))
  {
    $mensaplan = "Konnte Mensaplan nicht laden - Studentenwerk-Server offline?";
  }
  else
  {
    $mensa = simplexml_load_string($input) or die("Could not parse XML to object");
    
    $meals = [];
  	
    // Go through all of today's meals
    foreach($mensa->canteen[0]->day[0]->category as $mealcat)
    {
      if($mealcat['name'] != 'Dessertbuffet')
      {
        // Potentially alter lengthy names to make the first table column as narrow as possible,
        // swap spaces for nbsp's to prevent linebreaks, remove all dots because why are they even there
        $search = ['Heute am Aktionsstand (WOK)', 'Veganes Tagesangebot', 'Hauptkomponente', ' mit drei Beilagen', ' ', '.'];
        $replace = ['Buffetsaal', 'Vegan', 'Menü', '', '&nbsp;', ''];
        $category = str_replace($search, $replace, $mealcat['name']);
        
        // Remove additives lists from meal description (always in round brackets) and also remove
        // possible newlines that would cause the JS to complain about an "unterminated string literal"
        $name = preg_replace(['/ ?\([^(]*\)/', '/\s/'], ['', ' '], $mealcat->meal->name);
        
        $meals[] = "<th>$category</th><td>$name</td>";
      }
    }
    
    $mensaplan = "<table><tr>" . implode('</tr><tr>', $meals) . "</tr></table>";
  }
  
  echo $mensaplan;
?>