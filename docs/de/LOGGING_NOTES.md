# Logging-Hinweise

## Grundsatz

Die Logseite des Templates soll nicht selbst Logdateien aus `/opt/loxberry/log/plugins/<plugin>` zusammensuchen. Stattdessen sollte die LoxBerry-Logliste verwendet werden:

- Perl: `loglist_html()` bzw. `LoxBerry::Web::loglist_html()`
- PHP: `LBWeb::loglist_html()`

Diese Funktionen erzeugen den HTML-Code für die Logliste. Die Ausgabe ist LoxBerry-generiert und enthält z. B. Collapsible-Bereiche, Log-Level-Auswahl und Links zum LoxBerry Log Viewer.

## Wichtig: Logliste ist nicht gleich Dateiliste

Die sichtbare Logliste basiert auf LoxBerrys Logging-Verwaltung. Manuell in das Logverzeichnis kopierte Dateien erscheinen nicht zuverlässig in dieser Liste.

Wenn eine Datei nur als normale Datei in `/opt/loxberry/log/plugins/<plugin>/` liegt, kann sie im LoxBerry Log Manager ggf. unter „More Logfiles“ / „Mehr Logfiles“ auftauchen. Sie ist damit aber nicht automatisch Teil der session-basierten `loglist_html()`-Ausgabe.

## Demo-Log im Template

Das Template enthält ein Demo-Skript:

```text
bin/create_demo_log.sh
```

Dieses Skript nutzt die Bash-Logging-Bibliothek von LoxBerry:

```bash
. "$LBHOMEDIR/libs/bashlib/loxberry_log.sh"
```

Für Bash-Logging müssen die relevanten Variablen vor `LOGSTART` gesetzt werden:

```bash
PACKAGE="$PLUGIN"
NAME="demo"
LOGDIR=""
FILENAME="$LOGDIR_PLUGIN/demo.log"
APPEND=1
LOGSTART "Demo log started."
```

`PACKAGE` sollte dem Plugin-Folder entsprechen. Dadurch kann LoxBerry den Logeintrag dem Plugin zuordnen.

## Warum LoxBerry Logs wieder bereinigt

LoxBerry verwaltet Logs aktiv. Der Log Manager kann alte oder große Logs automatisch kürzen oder löschen. Außerdem kann eine Bereinigung erfolgen, wenn der Logbereich bzw. tmpfs/RAM-Disk wenig freien Speicher hat.

Daraus folgt:

- Logs dürfen nicht als dauerhafte Datenablage genutzt werden.
- Ein Plugin darf nicht davon ausgehen, dass eine Logdatei dauerhaft vorhanden bleibt.
- Eine Loganzeige muss damit umgehen können, dass eine Datei fehlt oder gekürzt wurde.
- Ein Daemon muss damit umgehen können, dass eine Logdatei während der Laufzeit gelöscht oder rotiert wird.

## Session-Logs für Aktionen

Für typische Webfrontend-Aktionen sind LoxBerry-Session-Logs sinnvoll:

- Konfiguration speichern
- Dienst starten
- Dienst stoppen
- Dienst neu starten
- Diagnose ausführen
- Export erzeugen

Session-Logs sind besser als eine große Sammeldatei, weil der Log Manager diese gezielt anzeigen und verwalten kann.

## Daemon-Logs

Ein dauerhaft laufender Dienst kann zusätzlich ein eigenes Daemon-Log führen. Dieses Log sollte aber robust gegen externe Löschung oder Rotation sein.

Für Python-Dienste ist `WatchedFileHandler` eine sinnvolle Vorlage, weil der Handler erkennt, wenn eine Datei von außen ersetzt oder rotiert wurde.

## Nicht empfohlen

- Logdateien manuell in das Logverzeichnis kopieren und erwarten, dass `loglist_html()` sie anzeigt.
- Logs als Zustandsspeicher verwenden.
- Endlos wachsende Sammellogs schreiben.
- Ohne `LOGEND` arbeitende Session-Logs erzeugen, falls es sich nicht um einen echten langlaufenden Prozess handelt.
