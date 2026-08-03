# Logging Notes

## Web frontend

CGI or PHP warnings are written to the Apache log. Frequent AJAX calls can quickly create many log entries.

Avoid generic helper names like `trim()` and use plugin prefixes such as `plugin_trim()`.

## Python daemon

The example `examples/python_logging_watchedfilehandler.py` is optional and only intended for plugins with Python daemons.

`WatchedFileHandler` reopens a logfile when it was externally rotated, replaced or deleted.
