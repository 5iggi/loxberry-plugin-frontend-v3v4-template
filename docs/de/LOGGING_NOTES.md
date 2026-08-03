# Logging-Hinweise

Diese Hinweise fassen robuste Logging-Muster für LoxBerry-Plugins zusammen. Ziel ist, dass Logdateien mit dem LoxBerry Log Manager, Webfrontend-Aktionen und dauerhaft laufenden Diensten zuverlässig zusammenspielen.

## Grundsätze

- Plugin-Logs sollten im vorgesehenen Plugin-Logverzeichnis liegen:

```text
/opt/loxberry/log/plugins/<pluginname>/
```

- Das Plugin sollte das Logverzeichnis bei Installation und bei Bedarf auch zur Laufzeit erzeugen.
- Eine Logdatei sollte nicht erst beim ersten echten Ereignis entstehen, sondern bereits bei Installation oder spätestens vor der Anzeige vorhanden sein.
- Webfrontend-Logs, Installationslogs und Daemon-Logs sollten getrennt betrachtet werden.
- Für dauerhaft laufende Dienste muss berücksichtigt werden, dass Logdateien extern gelöscht oder rotiert werden können.

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

Wenn das Skript als root läuft, können zusätzlich Besitzer und Gruppe gesetzt werden:

```bash
chown loxberry:loxberry "$LOGFILE" 2>/dev/null || true
```

Die Vorlagendateien `postinstall.sh` und `postroot.sh` enthalten dieses Prinzip bereits.

## Logdatei vor der Anzeige sicherstellen

Wenn im Webfrontend ein Button **Log anzeigen** vorhanden ist, sollte nicht direkt blind auf den LoxBerry Log Viewer verlinkt werden, falls die Datei fehlen kann.

Besser ist ein Zwischenschritt im Frontend:

1. Logverzeichnis prüfen oder anlegen.
2. Logdatei prüfen oder anlegen.
3. Danach auf den LoxBerry Log Viewer weiterleiten.

Die Beispiele `index.cgi` und `index.php` nutzen deshalb einen eigenen `showlog`-Parameter:

```text
index.cgi?showlog=daemon
index.php?showlog=daemon
```

Das Frontend stellt die Logdatei sicher und leitet danach auf den LoxBerry Log Viewer weiter.

## Dauerhaft laufende Python-Dienste

Bei dauerhaft laufenden Python-Diensten sollte kein einfacher `FileHandler` verwendet werden, wenn die Logdatei extern gelöscht oder rotiert werden kann.

Empfohlen ist `WatchedFileHandler`:

```python
from logging.handlers import WatchedFileHandler
handler = WatchedFileHandler(
    "/opt/loxberry/log/plugins/myplugin/myplugin.log",
    encoding="utf-8",
    delay=True,
)
```

`WatchedFileHandler` hilft, wenn eine Logdatei extern gelöscht oder rotiert wurde. Beim nächsten Logeintrag wird die Datei wieder geöffnet.

Das Beispiel liegt hier:

```text
examples/python_logging_watchedfilehandler.py
```

## Wichtiger Hinweis zu gelöschten Logdateien

Wenn eine Logdatei gelöscht wird, während ein Prozess sie noch geöffnet hat, kann der Prozess weiter in einen gelöschten Filehandle schreiben.

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

Ein dauerhaft laufender Daemon kann zusätzlich ein eigenes Daemon-Log behalten.

## Empfehlungen für neue Plugins

- Logverzeichnis und Logdatei bereits bei Installation anlegen.
- Bei Loganzeige im Webfrontend die Logdatei vorher sicherstellen.
- Bei Python-Daemons `WatchedFileHandler` verwenden.
- Für Webfrontend- und Skriptaktionen LoxBerry-Session-Logs nutzen.
- Logdateien nicht unkontrolliert anwachsen lassen.
- Keine generischen Hilfsfunktionen wie `trim()` verwenden. Besser plugin-spezifische Namen verwenden, z. B. `myplugin_trim()`.
- Logdateien und Loglinks regelmäßig mit dem LoxBerry Log Manager testen.

## Prüfbefehle

```bash
ls -lh /opt/loxberry/log/plugins/<pluginname>/
PID=$(systemctl show -p MainPID --value <service>.service)
sudo ls -l /proc/$PID/fd | grep -i deleted
journalctl -u <service>.service --since "10 minutes ago" --no-pager
perl -c /opt/loxberry/webfrontend/htmlauth/plugins/<pluginname>/index.cgi
python3 -m py_compile /opt/loxberry/bin/plugins/<pluginname>/<daemon>.py
```
