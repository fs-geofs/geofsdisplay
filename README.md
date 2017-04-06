# Geofsdisplay

### Plakate
Es koennen Bilder und PDFs eingefuegt werden.

> **Achtung:** Plakate müssen immer im DIN-Format hochkant sein. Ansonsten werden diese nicht korrekt angezeigt!

Der Dateiname der Plakate muss wie folgt aufgebaut sein:
`nn-ss-name.(png|jpg|pdf)`

 - nn: Zahl zur Sortierung der Plakate
 - ss: Zeit in Sekunden, die das Plakat angezeigt werden soll
 - name: beliebiger Name des Plakats

### News
#### Dateiname
`Titel der News.txt`
#### Inhalt der Datei:
Newstext

### Installation
```bash
cd /var/www
sudo git clone https://github.com/fs-geofs/geofsdisplay.git
sudo chown www-data:www-data geofsdisplay -R
sudo ln -sfb $PWD/geofsdisplay/geofsdisplay.service /etc/systemd/system/
```

Abschlie-end sollte der autoresize service fuer die Plakate noch konfiguriert werden.
Anpassen der env `DIRECTORY` und `RESOLUTION`, je nach Zielbildschirm:
```bash
sudo editor geofsdisplay/geofsdisplay.service
```

