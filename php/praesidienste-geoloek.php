<?php
$url =  getenv("GEOFS_BASE_PAGE") ?? "https://geofs.uni-muenster.de/"; 
$url .= "wp/geoloek/praesenzzeiten/";

$input = @file_get_contents($url) or die('Could not access file: $url');

if( preg_match( '~(<table[^>]*id="praesenzzeiten"[^>]*>.*</table>)~si', $input, $content ) ) {
    echo trim($content[1]);
}
?>

