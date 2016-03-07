<?php $url =  "http://www.studentenwerk-muenster.de/de/essen-a-trinken/mensen/mensa-am-ring"; 
$input = @file_get_contents($url) or die('Could not access file: $url'); 
$mensaplan;
$tag = date("w");
$tage = array("sonntag","montag","dienstag","mittwoch","donnerstag","freitag","samstag");

	if( preg_match( '~<td id="'.$tage[$tag].'_menu1">\s*(.*?)\s*</td>~si', $input, $content ) ) 
	{ 
	    $mensaplan = "Menu 1: ".addslashes(trim( $content[1] ))."<br>";
	}
	if( preg_match( '~<td id="'.$tage[$tag].'_menu2">\s*(.*?)\s*</td>~si', $input, $content ) ) 
	{ 
	    $mensaplan = $mensaplan."Menu 2: ".addslashes(trim( $content[1] ))."<br>";
	}
	if( preg_match( '~<td id="'.$tage[$tag].'_menu3">\s*(.*?)\s*</td>~si', $input, $content ) ) 
	{ 
	    $mensaplan = $mensaplan."Menu 3: ".addslashes(trim( $content[1] ));
	}
    ?>
	    <script>
	    	addNews("Heute in der Mensa am Ring:.txt", "<?php echo $mensaplan ?>");
	    </script>
	<?php
?>
