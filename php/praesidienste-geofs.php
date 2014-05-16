<?php $url =  "https://geofs.uni-muenster.de/geoinf/doku.php?id=fachschaft:praesenzzeiten"; 
$input = @file_get_contents($url) or die('Could not access file: $url'); 

if( preg_match( '~<div class="table sectionedit3">\s*(.*?)\s*</div>~si', $input, $content ) ) 
{ 
    echo trim( $content[1] ); 
}
?>