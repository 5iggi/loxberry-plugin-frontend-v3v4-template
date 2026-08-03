# Logging-Hinweise

## Webfrontend

CGI- oder PHP-Warnungen landen im Apache-Log. Häufige AJAX-Aufrufe können dadurch sehr schnell viele Logzeilen erzeugen.

Vermeide generische Funktionsnamen:

```perl
sub trim { ... }
```

Nutze stattdessen:

```perl
sub plugin_trim { ... }
```

## Python-Daemon

Wenn ein Daemon in eine feste Datei schreibt, kann diese Datei extern gelöscht oder rotiert werden. Der Prozess schreibt dann eventuell weiter in einen gelöschten Filehandle.

Empfehlung:

```python
from logging.handlers import WatchedFileHandler
handler = WatchedFileHandler(logfile, mode="a", encoding="utf-8")
```

`WatchedFileHandler` öffnet die Datei wieder neu, wenn sie extern ersetzt oder gelöscht wurde.
