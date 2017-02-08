<?php
// Ordnername
$ordner = "./displaycontent/news"; //auch komplette Pfade möglich ($ordner = "download/files";)
$fileExts = array("txt", "md");

// Ordner auslesen und Array in Variable speichern
$alledateien = scandir($ordner); // Sortierung A-Z
// Sortierung Z-A mit scandir($ordner, 1)

// Schleife um Array "$alledateien" aus scandir Funktion auszugeben
// Einzeldateien werden dabei in der Variabel $datei abgelegt
foreach ($alledateien as $datei) {

    // Zusammentragen der Dateiinfo
    $dateiinfo = pathinfo($ordner."/".$datei);
    //Folgende Variablen stehen nach pathinfo zur Verfügung
    // $dateiinfo['filename'] =Dateiname ohne Dateiendung  *erst mit PHP 5.2
    // $dateiinfo['dirname'] = Verzeichnisname
    // $dateiinfo['extension'] = Dateityp -/endung
    // $dateiinfo['basename'] = voller Dateiname mit Dateiendung

    // nur Dateien mit txt extension zulassen
    if (in_array($dateiinfo['extension'], $fileExts)) {

        $newstext = str_replace("'", "&apos;", implode(file($ordner."/".$datei, FILE_IGNORE_NEW_LINES), "<br>"));
        echo "addNews('$dateiinfo[filename]', '$newstext');\n";
    };
 };
?>
