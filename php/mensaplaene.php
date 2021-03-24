<?php
  $url = "https://bburke.gitlab.io/mymensa2openmensa/feed/ring.xml";
  /* 
    Sadly, the Studierendenwerk doesn't provide an API or XML files anymore.
    Instead, all we've got is the "MyMensa" webapp.
    But luckily, some lovely people have written a parser for it!
    Its output is fed into OpenMensa and can be retrieved from above link.
    See also:
    - https://github.com/fs-geofs/geofsdisplay/issues/14
    - https://github.com/chk1/stw2openmensa/issues/15#issuecomment-728103161
    - https://gitlab.com/BBurke/mymensa2openmensa
    - https://bburke.gitlab.io/mymensa2openmensa/index.html
    - https://openmensa.org/c/1169/
  */
   
  if (! $input = @file_get_contents($url))
  {
    $mensaplan = "Konnte Mensaplan nicht laden - Studentenwerk-Server offline?";
  }
  else
  {
    $mensa = simplexml_load_string($input) or die("Could not parse XML to object");
    $mensa->registerXPathNamespace("om", "http://openmensa.org/open-mensa-v2");  // needed for XPath to work, see https://www.php.net/manual/de/simplexml.examples-basic.php#102599
    
    $meals = [];
  	
    // Go through all of today's meals
    foreach($mensa->xpath('//om:day[@date="' . date('Y-m-d') . '"]/om:category') as $mealcat)
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