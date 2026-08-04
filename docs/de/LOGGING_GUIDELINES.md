# Logging-Guidelines für LoxBerry-Plugins

## Ziel

Diese Hinweise fassen neutrale Empfehlungen für robuste Logs in LoxBerry-Plugins zusammen. Sie berücksichtigen insbesondere, dass LoxBerry Logs aktiv verwaltet und bereinigt.

## Logverzeichnis

Plugin-Logs gehören grundsätzlich in:

```text
/opt/loxberry/log/plugins/<pluginname>/
```

Das Logverzeichnis kann bei Installation oder bei Bedarf zur Laufzeit erstellt werden. Ein Plugin sollte aber nicht davon ausgehen, dass dort abgelegte Dateien dauerhaft erhalten bleiben.

## LoxBerry bereinigt Logs

LoxBerry kann Logdateien automatisch kürzen oder löschen, wenn:

- Logdateien alt sind,
- Logdateien sehr groß sind,
- im Logbereich bzw. tmpfs/RAM-Disk zu wenig Platz frei ist.

Der Log Manager zeigt im Bereich „Logfiles“ vor allem Logging-Sessions. Das ist nicht identisch mit einer rohen Dateiliste. Eine Session kann sichtbar sein, obwohl die zugrunde liegende Datei bereits gekürzt oder gelöscht wurde.

## Logdatenbank

LoxBerry führt eine Logdatenbank. Diese Datenbank wird für die Loglisten und den Log Manager verwendet. Wenn die Datenbank nicht beschreibbar ist, z. B. wegen vollem tmpfs, kann die Logerstellung fehlschlagen.

Prüfpunkte:

```bash
df -h /tmp /opt/loxberry/log 2>/dev/null
ls -lh /opt/loxberry/log/plugins/<pluginname>/
```

## Session-Logs

Für Aktionen sollten bevorzugt Session-Logs über das LoxBerry Logging SDK erstellt werden.

Beispiel für Bash:

```bash
. "$LBHOMEDIR/libs/bashlib/loxberry_log.sh"
PACKAGE="$PLUGIN"
NAME="action"
FILENAME="$LBPLOG/$PLUGIN/action.log"
APPEND=1
LOGSTART "Action started."
LOGINF "Doing something."
LOGOK "Action finished."
LOGEND "Action finished."
```

Wichtig:

- `PACKAGE` entspricht dem Plugin-Folder.
- `NAME` ist die Loggruppe.
- `FILENAME` muss gesetzt sein.
- `LOGEND` sollte bei abgeschlossenen Aktionen gesetzt werden.

## Daemon-Logs

Ein Daemon kann eine eigene dauerhafte Logdatei schreiben. Diese Datei kann jedoch durch LoxBerry oder externe Logpflege gelöscht oder rotiert werden.

Empfehlungen:

- Daemon kann beim Start das Logverzeichnis sicherstellen.
- Daemon sollte gegen gelöschte oder rotierte Logdateien robust sein.
- Ein Dienstneustart sollte das Logfile wieder anlegen können.
- Logs nicht als Zustandsspeicher verwenden.

## Python-Daemons

Für Python-Daemons ist `WatchedFileHandler` eine robuste Option:

```python
from logging.handlers import WatchedFileHandler
handler = WatchedFileHandler(
    "/opt/loxberry/log/plugins/myplugin/myplugin.log",
    encoding="utf-8",
    delay=True,
)
```

Der Handler erkennt, wenn eine Logdatei von außen ersetzt oder rotiert wurde, und öffnet die Datei neu.

## Gelöschte offene Logdateien

Wenn ein Prozess eine Logdatei offen hält und die Datei außerhalb des Prozesses gelöscht wird, kann der Prozess weiter in einen gelöschten Filehandle schreiben. Die Datei ist dann im Verzeichnis nicht mehr sichtbar, belegt aber weiterhin Speicher.

Prüfung:

```bash
PID=$(systemctl show -p MainPID --value <service>.service)
sudo ls -l /proc/$PID/fd | grep -i deleted
```

Kurzfristig hilft ein Dienstneustart. Langfristig sollte der Dienst das Logfile robust neu öffnen können.

## Empfehlungen

- Logs nur für Diagnose verwenden, nicht als Datenbank.
- Aktionen als LoxBerry-Session-Logs erfassen.
- Daemon-Logs klein halten und robust neu öffnen können.
- Loganzeige immer über LoxBerry-Funktionen oder Logviewer realisieren.
- Manuell kopierte Logdateien nicht als Grundlage für `loglist_html()` verwenden.
- Logverhalten regelmäßig unter LoxBerry v3 und v4 testen.
