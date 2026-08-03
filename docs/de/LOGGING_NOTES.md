# Logging-Hinweise

LoxBerry-Logging ist für Aktionen session-basiert. Für Webfrontend-Aktionen, Installationsschritte und Diagnose sollte bevorzugt das LoxBerry Logging SDK genutzt werden.

Das Template nutzt für den Demo-Logbutton die Plugin-Hauptlogdatei:

```text
/opt/loxberry/log/plugins/<plugin>/<plugin>.log
```

Der Button ruft zuerst das eigene Frontend auf:

```text
index.cgi?showlog=plugin
index.php?showlog=plugin
```

Dadurch wird die Logdatei vor der Anzeige sichergestellt und danach im LoxBerry Log Viewer geöffnet.
