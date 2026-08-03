# Logging Notes

## Web frontend

CGI or PHP warnings are written to the Apache log. Frequent AJAX calls can quickly create many log entries.

Avoid generic helper names:

```perl
sub trim { ... }
```

Use a plugin prefix instead:

```perl
sub plugin_trim { ... }
```

## Python daemon

If a daemon writes to a fixed logfile, that file may be deleted or rotated externally. The process may then continue writing to a deleted file handle.

Recommended:

```python
from logging.handlers import WatchedFileHandler
handler = WatchedFileHandler(logfile, mode="a", encoding="utf-8")
```

`WatchedFileHandler` reopens the file when it was externally replaced or deleted.
