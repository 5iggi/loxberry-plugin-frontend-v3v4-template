# Hinweise zur Log-Datei-Erstellung in LoxBerry-Plugins

Diese Hinweise fassen allgemeine Empfehlungen für zukünftige LoxBerry-Plugins zusammen. Ziel ist eine robuste Log-Ausgabe, die sowohl mit dem LoxBerry Log Manager als auch mit dauerhaft laufenden Diensten zuverlässig zusammenspielt.

## Grundsätze

- Plugin-Logs sollten im vorgesehenen Plugin-Logverzeichnis abgelegt werden:

```text
/opt/loxberry/log/plugins/<pluginname>/
```

- Das Plugin sollte das Logverzeichnis bei Installation und bei Bedarf auch zur Laufzeit erzeugen.
- Eine Logdatei sollte nicht erst beim ersten echten Ereignis entstehen, sondern bereits bei Installation oder beim ersten Öffnen der Logansicht vorhanden sein.
- Für dauerhaft laufende Dienste sollte berücksichtigt werden, dass Logdateien extern gelöscht oder rotiert werden können.
- Webfrontend-Logs, Installationslogs und Daemon-Logs sollten möglichst getrennt betrachtet werden.

## Empfohlene Struktur

```text
/opt/loxberry/log/plugins/<pluginname>/<pluginname>.log
/opt/loxberry/log/plugins/<pluginname>/<weitere-session-logs>.log
```

Beispiel:

```text
/opt/loxberry/log/plugins/myplugin/myplugin.log
```

## Installation und Update

Installations- oder Root-Skripte sollten das Logverzeichnis und eine leere Logdatei sicher anlegen.

Beispiel für `postinstall.sh` oder `postroot.sh`:

```bash
LOGDIR="$PLOGS"
LOGFILE="$LOGDIR/myplugin.log"

mkdir -p "$LOGDIR"

if [ ! -f "$LOGFILE" ]; then
    touch "$LOGFILE" || true
fi

chmod 664 "$LOGFILE" 2>/dev/null || true
```

Wenn das Skript als `root` läuft, können zusätzlich Besitzer und Gruppe gesetzt werden:

```bash
chown loxberry:loxberry "$LOGFILE" 2>/dev/null || true
```

## Logdatei vor der Anzeige sicherstellen

Wenn im Webfrontend ein Button „Log anzeigen“ vorhanden ist, sollte nicht direkt blind auf den LoxBerry Log Viewer verlinkt werden, falls die Datei fehlen kann.

Besser ist ein Zwischenschritt im `index.cgi`:

1. Logverzeichnis prüfen oder anlegen.
2. Logdatei prüfen oder anlegen.
3. Danach auf den LoxBerry Log Viewer weiterleiten.

Beispielprinzip:

```perl
if ($cgi->param('showlog')) {
    my $logdir  = '/opt/loxberry/log/plugins/myplugin';
    my $logfile = "$logdir/myplugin.log";

    mkdir $logdir if !-d $logdir;

    if (!-e $logfile) {
        open my $fh, '>>', $logfile;
        close $fh if $fh;
        chmod 0664, $logfile;
    }

    print $cgi->redirect('/admin/system/tools/logfile.cgi?logfile=plugins/myplugin/myplugin.log&header=html&format=template');
    exit;
}
```

Der Button im Template zeigt dann auf das eigene CGI:

```html
<a href="index.cgi?showlog=daemon">Log anzeigen</a>
```

## Dauerhaft laufende Python-Dienste

Bei dauerhaft laufenden Python-Diensten sollte kein einfacher `FileHandler` verwendet werden, wenn die Logdatei extern gelöscht oder rotiert werden kann.

Empfohlen ist `WatchedFileHandler`:

```python
import logging
from logging.handlers import WatchedFileHandler

handler = WatchedFileHandler(
    "/opt/loxberry/log/plugins/myplugin/myplugin.log",
    encoding="utf-8",
    delay=True,
)

formatter = logging.Formatter("%(asctime)s %(levelname)s: %(message)s")
handler.setFormatter(formatter)

root = logging.getLogger()
root.addHandler(handler)
root.setLevel(logging.INFO)
```

`WatchedFileHandler` hilft, wenn eine Logdatei extern gelöscht oder rotiert wurde. Beim nächsten Logeintrag wird die Datei wieder geöffnet.

## Wichtiger Hinweis zu gelöschten Logdateien

Wenn eine Logdatei gelöscht wird, während ein Prozess sie noch geöffnet hat, kann der Prozess weiter in einen gelöschten Filehandle schreiben. Das sieht zum Beispiel so aus:

```text
/opt/loxberry/log/plugins/myplugin/myplugin.log (deleted)
```

Prüfung:

```bash
PID=$(systemctl show -p MainPID --value myplugin.service)
sudo ls -l /proc/$PID/fd | grep -i deleted
```

In diesem Fall hilft kurzfristig ein Neustart des Dienstes. Dauerhaft sollte die Anwendung einen robusten Loghandler verwenden und die Logdatei vor der Anzeige im Webfrontend sicherstellen.

## LoxBerry-Session-Logs

Für Webfrontend-Aktionen, Installationsschritte und Skriptaufrufe sind LoxBerry-Session-Logs oft besser geeignet als ein dauerhaftes Einzel-Logfile.

Typische Einsatzbereiche:

- Konfiguration speichern
- Dienst starten, stoppen oder neu starten
- Export-Funktionen
- Installations- und Update-Skripte
- Diagnose-Aktionen

Für solche Aktionen kann das LoxBerry Logging SDK verwendet werden. Ein dauerhaft laufender Daemon kann zusätzlich ein eigenes Daemon-Log behalten.

## Empfehlungen für neue Plugins

- Logverzeichnis und Logdatei bereits bei Installation anlegen.
- Bei Loganzeige im Webfrontend die Logdatei vorher sicherstellen.
- Bei Python-Daemons `WatchedFileHandler` verwenden.
- Für Webfrontend- und Skriptaktionen LoxBerry-Session-Logs nutzen.
- Logdateien nicht unkontrolliert anwachsen lassen.
- Keine generischen Hilfsfunktionen wie `trim()` im CGI verwenden, wenn Namenskollisionen möglich sind. Besser plugin-spezifische Namen verwenden, z. B. `myplugin_trim()`.
- Logdateien und Loglinks regelmäßig mit dem LoxBerry Log Manager testen.

## Prüfbefehle

```bash
ls -lh /opt/loxberry/log/plugins/<pluginname>/
```

```bash
PID=$(systemctl show -p MainPID --value <service>.service)
sudo ls -l /proc/$PID/fd | grep -i deleted
```

```bash
journalctl -u <service>.service --since "10 minutes ago" --no-pager
```

```bash
perl -c /opt/loxberry/webfrontend/htmlauth/plugins/<pluginname>/index.cgi
```

```bash
python3 -m py_compile /opt/loxberry/bin/plugins/<pluginname>/<daemon>.py
```

## Kurzfassung

Für robuste LoxBerry-Plugin-Logs sollte ein Plugin die Logdatei nicht nur schreiben, sondern auch deren Lebenszyklus berücksichtigen:

- bei Installation anlegen,
- vor Anzeige sicherstellen,
- bei Daemons mit externem Löschen oder Rotation umgehen,
- Webfrontend-Aktionen separat als LoxBerry-Session-Logs erfassen.
