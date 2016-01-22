<?php $url =  "https://geofs.uni-muenster.de/wp/geoloek/praesenzzeiten"; 
$input = @file_get_contents($url) or die('Could not access file: $url'); 

// remove any attributes from tags, to ensure there is no unwanted styling
$input = preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/i",'<$1$2>', $input);

// replace newlines with ", " to make better use of the available space
/* $input = preg_replace("/<br\/?>/i", ", ", $input); */

if( preg_match( '~<table\s*>\s*(.*?)\s*</table>~si', $input, $content ) ) 
{ 
    echo '<table>';
    echo trim ( $content[1] );
    echo '</table>';
}
?>

