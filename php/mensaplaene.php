<?php
  $url = "https://bburke.gitlab.io/mymensa2openmensa/feed/ring.xml";
  /* Sadly, the Studierendenwerk doesn't provide an API or XML files anymore.
    Instead, all we've got is the "MyMensa" webapp.
    But luckily, some lovely people have written a parser for it!
    Its output is fed into OpenMensa and can be retrieved from above link.
  */
   
  if (! $input = @file_get_contents($url))
  {
    $mensaplan = "Konnte Mensaplan nicht laden. Studiwerk- oder Parser-Server offline?";
  }
  else
  {
    $mensa = simplexml_load_string($input) or die("Could not parse XML to object");
    $mensa->registerXPathNamespace("om", "http://openmensa.org/open-mensa-v2");  // needed for XPath to work
    $base4today = '//om:day[@date="' . date('Y-m-d') . '"]';  // XPath base string to select the <day> node for today
    $mensaplan = '';

    // Remove additives lists from meal description (always in round brackets)
    function format_name($meal) { return trim(preg_replace('/ ?:?\([^(]*\)/', '', $meal->name)); }
    // Convert dot to comma (correct German decimal delimiter), add Euro sign, put in brackets
    function format_price($meal) { return $meal->price ? '(' . str_replace('.', ',', $meal->price) . '&nbsp;€)' : ''; }

    // Hole ALLE Kategorien für den heutigen Tag auf einmal
    $categories = $mensa->xpath($base4today . '/om:category');

    // Prüfen, ob es für heute überhaupt Einträge gibt (z.B. am Wochenende geschlossen)
    if (!$categories) {
        $mensaplan = "<p>Heute gibt es keinen Speiseplan (oder die Mensa ist geschlossen).</p>";
    } else {
        // Alle gefundenen Kategorien dynamisch durchlaufen
        foreach($categories as $category) {
            // Kategoriename aus dem Attribut "name" auslesen
            $catName = (string) $category['name'];
            $mensaplan .= "<h4>" . htmlspecialchars($catName) . "</h4><ul>";

            // Alle Gerichte innerhalb dieser Kategorie durchlaufen
            foreach($category->meal as $meal) {
                $name = format_name($meal);
                $price = format_price($meal);
                
                // Preis nur anhängen, wenn einer existiert (inkl. Leerzeichen davor)
                $priceStr = $price ? " " . $price : "";
                
                $mensaplan .= "<li>{$name}{$priceStr}</li>";
            }
            $mensaplan .= '</ul>';
        }
    }
  }
  
  echo $mensaplan;
?>