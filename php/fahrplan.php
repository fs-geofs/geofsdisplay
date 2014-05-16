<?php
echo "<img src='images/busstop.png' style='height: 45px; margin: 2px; left: 180px; position: absolute;'><br><br><br><br><br><br><br><br><br><br><br><br><br>";
echo file_get_contents('http://www.stadtwerke-muenster.de/fis/ajaxrequest.php?mastnr=5992');
// echo "<br>";
// echo file_get_contents('http://www.stadtwerke-muenster.de/fis/ajaxrequest.php?mastnr=5202');
?>