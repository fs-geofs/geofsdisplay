<?php
echo "<div id='einwaerts'>";
echo file_get_contents('http://www.stadtwerke-muenster.de/fis/ajaxrequest.php?mastnr=5992');
echo "</div><div id='auswaerts'>";
echo file_get_contents('http://www.stadtwerke-muenster.de/fis/ajaxrequest.php?mastnr=5991');
echo "</div>";
// echo "<br>";
// echo file_get_contents('http://www.stadtwerke-muenster.de/fis/ajaxrequest.php?mastnr=5202');
?>