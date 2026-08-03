# Logging-Hinweise

## Webfrontend

CGI- oder PHP-Warnungen landen im Apache-Log. Häufige AJAX-Aufrufe können sehr schnell viele Logzeilen erzeugen.

Vermeide generische Funktionsnamen wie `trim()` und nutze Plugin-Präfixe, z. B. `plugin_trim()`.

## Python-Daemon

Das Beispiel `examples/python_logging_watchedfilehandler.py` ist optional und nur für Plugins mit Python-Daemon gedacht.

`WatchedFileHandler` öffnet eine Logdatei neu, wenn sie extern rotiert, ersetzt oder gelöscht wurde.
