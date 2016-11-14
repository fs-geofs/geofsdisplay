<?php
  $url = "http://speiseplan.stw-muenster.de/mensa_am_ring.xml";
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
   
  $input = @file_get_contents($url) or die("Could not access file: $url"); 
  $mensa = simplexml_load_string($input) or die("Could not parse XML to object");
  
  $meals = [];
	
  // Go through all of today's meals
  foreach($mensa->date[0]->item as $meal)
  {
    if($meal->category != 'Dessertbuffet')
    {
      // Potentially remove "Heute am " from "Heute am Aktionsstand WOK" to make it shorter
      $category = str_replace('Heute am ', '', $meal->category);
      // Remove additives list from meal description (always in round brackets) and also remove
      // possible newlines that would cause the JS to complain about an "unterminated string literal"
      $name = preg_replace(['/ ?\([^(]*\)/', '/\s/'], ['', ' '], $meal->meal);
      
      $meals[] = "$category: $name";
    }
  }
  
  $mensaplan = implode('<br>', $meals);
?>

<script>
  addNews("Heute in der Mensa am Ring:", "<div id='mensaplan'><?php echo addslashes($mensaplan); ?></div>");
</script>
