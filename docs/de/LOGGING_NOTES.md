# Logging-Hinweise

Diese Hinweise fassen robuste, LoxBerry-gerechte Logging-Muster für Plugins zusammen.

## LoxBerry-Grundgedanke

LoxBerry stellt ein eigenes Logging SDK bereit. Dieses ist besonders für Webfrontend-Aktionen, Installationsschritte, Skriptaufrufe und Diagnoseaktionen gedacht. Das LoxBerry-Logging ist session-basiert: Ein Skriptlauf erzeugt eine eigene Log-Session, die klein, übersichtlich und im LoxBerry Log Manager gut zuordenbar ist.

Für dauerhaft laufende Dienste, z. B. Python-Daemons, kann zusätzlich ein eigenes dauerhaftes Daemon-Log sinnvoll sein.

## Empfohlene Trennung

```text
Webfrontend-Aktion     -> LoxBerry Logging SDK / Session-Log
Installation/Upgrade   -> LoxBerry Logging SDK / Session-Log oder Installationslog
Dauerhafter Daemon     -> eigenes Daemon-Log mit robustem Loghandler
Demo-/Plugin-Hauptlog  -> /opt/loxberry/log/plugins/<plugin>/<plugin>.log
```

## Standard-Logdatei im Template

Das Template verwendet für den Demo-Logbutton bewusst keine feste Datei `daemon.log`, sondern die Plugin-Hauptlogdatei:

```text
/opt/loxberry/log/plugins/<plugin>/<plugin>.log
```

Der Button im Template ruft nicht direkt den Log Viewer auf, sondern zuerst das eigene Frontend:

```text
index.cgi?showlog=plugin
index.php?showlog=plugin
```

Das Frontend stellt dabei sicher, dass Logverzeichnis und Logdatei existieren, und leitet anschließend zum LoxBerry Log Viewer weiter.

## Logdatei bei Installation anlegen

Die Beispielskripte `postinstall.sh` und `postroot.sh` legen die Demo-/Plugin-Hauptlogdatei an:

```bash
PLUGIN="${3:-PLUGINNAME}"
BASE="${LBHOMEDIR:-/opt/loxberry}"
LOGDIR="${LBPLOG:-$BASE/log/plugins}/$PLUGIN"
LOGFILE="$LOGDIR/$PLUGIN.log"

mkdir -p "$LOGDIR" || true
if [ ! -f "$LOGFILE" ]; then
    touch "$LOGFILE" || true
fi
chmod 664 "$LOGFILE" 2>/dev/null || true
```

Wenn das Skript als root läuft, kann zusätzlich gesetzt werden:

```bash
chown loxberry:loxberry "$LOGFILE" 2>/dev/null || true
```

## Webfrontend-Aktionen

Für echte Aktionen wie Speichern, Neustart, Export, Diagnose oder Installationsschritte sollte bevorzugt das LoxBerry Logging SDK verwendet werden. Ein einzelnes dauerhaftes Sammellog für alle Aktionen ist nicht der Kern des LoxBerry-Logging-Konzepts.

## Dauerhaft laufende Python-Dienste

Für Python-Daemons ist `WatchedFileHandler` sinnvoll, weil Logdateien extern gelöscht oder rotiert werden können:

```python
from logging.handlers import WatchedFileHandler
handler = WatchedFileHandler(
    "/opt/loxberry/log/plugins/myplugin/myplugin.log",
    encoding="utf-8",
    delay=True,
)
```

Das Beispiel liegt hier:

```text
examples/python_logging_watchedfilehandler.py
```

## Prüfung gelöschter Logdateien

Wenn eine Logdatei gelöscht wird, während ein Prozess sie noch geöffnet hat, kann der Prozess weiter in einen gelöschten Filehandle schreiben.

```bash
PID=$(systemctl show -p MainPID --value myplugin.service)
sudo ls -l /proc/$PID/fd | grep -i deleted
```

Kurzfristig hilft ein Neustart des Dienstes. Dauerhaft sollte ein robuster Loghandler verwendet werden.

## Prüfbefehle

```bash
ls -lh /opt/loxberry/log/plugins/<pluginname>/
perl -c /opt/loxberry/webfrontend/htmlauth/plugins/<pluginname>/index.cgi
php -l /opt/loxberry/webfrontend/htmlauth/plugins/<pluginname>/index.php
python3 -m py_compile /opt/loxberry/bin/plugins/<pluginname>/<daemon>.py
```
