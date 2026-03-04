<?php
echo "<div id='einwaerts'>";
echo file_get_contents('https://fis.stadtwerke-muenster.de/ajaxrequest.php?mastnr=4599102');
echo "</div><div id='auswaerts'>";
echo file_get_contents('https://fis.stadtwerke-muenster.de/ajaxrequest.php?mastnr=4599101');
echo "</div>";
// echo "<br>";
// echo file_get_contents('http://www.stadtwerke-muenster.de/fis/ajaxrequest.php?mastnr=5202');
?>