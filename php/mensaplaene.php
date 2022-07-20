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
    $mensaplan = "Konnte Mensaplan nicht laden. Studiwerk- oder Parser-Server offline?";
  }
  else
  {
    $mensa = simplexml_load_string($input) or die("Could not parse XML to object");
    $mensa->registerXPathNamespace("om", "http://openmensa.org/open-mensa-v2");  // needed for XPath to work, see https://www.php.net/manual/de/simplexml.examples-basic.php#102599
    $base4today = '//om:day[@date="' . date('Y-m-d') . '"]';  // XPath base string to select the <day> node for today
    $mensaplan = '';

    // Remove additives lists from meal description (always in round brackets)
    function format_name($meal) { return trim(preg_replace('/ ?\([^(]*\)/', '', $meal->name)); }
    // Convert dot to comma (correct German decimal delimiter), add Euro sign, put in brackets
    function format_price($meal) { return $meal->price ? '(' . str_replace('.', ',', $meal->price) . '&nbsp;€)' : ''; }
    
    // Go through all of today's proper meals (named "Speisenangebot", "Heute am Aktionsstand (WOK)", "Imbiss X", where X=1,2,3...)
    foreach(['Speisenangebot'=>'Oben', 'Heute'=>'Unten', 'Imbiss'=>'Imbiss'] as $catname_in_xml => $catname_to_display) {
      $mensaplan .= "<h4>$catname_to_display</h4><ul>";
      $results = $mensa->xpath($base4today . '/om:category[starts-with(@name, "' . $catname_in_xml . '")]');
      foreach($results as $mealcat) {
        foreach($mealcat as $meal) {
          $name = format_name($meal);
          $price = format_price($meal);
          $mensaplan .= "<li>$name $price</li>";
        }
      }
      $mensaplan .= '</ul>';
    }

    // Go through all side dishes (named "Beilagen X" and "Dessert X", where X=1,2,3...)
    $mensaplan .= '<h4>Beilagen</h4><ul>';
    $results = $mensa->xpath($base4today . '/om:category[starts-with(@name, "Beilage") or starts-with(@name, "Dessert")]');
    // Sort mealcats by price in ascending order (all side dishes with the same price are in the same mealcat)
    // The prices are all in the same format, so a simple string compare is sufficient
    usort($results, fn($a, $b) => strcmp($a->meal[0]->price[0], $b->meal[0]->price[0]));
    foreach($results as $mealcat) {
      $names = [];  // each side dish of the same price is one "meal" in this mealcat -> gather them
      $price = format_price($mealcat->meal[0]);  // all have the same price, so simply take the first's
      foreach($mealcat as $meal) {
        $names [] = format_name($meal);
      }
      $mensaplan .= "<li>" . implode($names, ', ') . " $price</li>";
    }
    $mensaplan .= '</ul>';

    // Catch-all for the rest (categories that haven't been queried explicitly beforehand)
    $catnames_so_far = ['Speisenangebot', 'Heute', 'Imbiss', 'Beilage', 'Dessert'];
    // Build selectors as before
    $catnames_so_far = array_map(fn($v) => "starts-with(@name, '$v')", $catnames_so_far);
    // Use the "not(...)" function to select all nodes which are the opposite of what was selected so far
    $results = $mensa->xpath($base4today . '/om:category[not(' . implode($catnames_so_far, " or ") . ')]');
    // Just output everything we found very simply
    foreach($results as $mealcat) {
      foreach($mealcat as $meal) {
        $mensaplan .= "<h4>Info</h4><p>$meal->name</p>";
      }
    }
  }
  
  echo $mensaplan;
?>