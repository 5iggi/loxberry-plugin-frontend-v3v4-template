# Logging-Hinweise

## Webfrontend

CGI-Warnungen landen im Apache-Log. Hauefige AJAX-Aufrufe koennen dadurch sehr schnell viele Logzeilen erzeugen.

Vermeide generische Funktionsnamen:

```perl
sub trim { ... }
```

Nutze stattdessen:

```perl
sub plugin_trim { ... }
```

## Python-Daemon

Wenn ein Daemon in eine feste Datei schreibt, kann diese Datei extern geloescht oder rotiert werden. Der Prozess schreibt dann eventuell weiter in einen geloeschten Filehandle.

Empfehlung:

```python
from logging.handlers import WatchedFileHandler

handler = WatchedFileHandler(logfile, mode="a", encoding="utf-8")
```

`WatchedFileHandler` oeffnet die Datei wieder neu, wenn sie extern ersetzt oder geloescht wurde.
