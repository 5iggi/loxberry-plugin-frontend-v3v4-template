# Logging Notes

These notes summarize robust logging patterns for LoxBerry plugins. The goal is reliable interaction with the LoxBerry Log Manager, web frontend actions and long-running services.

## Principles

- Plugin logs should be stored in the plugin log directory:

```text
/opt/loxberry/log/plugins/<pluginname>/
```

- The plugin should create the log directory during installation and also at runtime if needed.
- A logfile should not only appear after the first real event. It should already exist after installation or at least before the log view is opened.
- Web frontend logs, installation logs and daemon logs should be treated separately.
- Long-running services must handle externally deleted or rotated logfiles.

## Recommended structure

```text
/opt/loxberry/log/plugins/<pluginname>/<pluginname>.log
/opt/loxberry/log/plugins/<pluginname>/<additional-session-logs>.log
```

Example:

```text
/opt/loxberry/log/plugins/myplugin/myplugin.log
```

## Installation and update

Installation or root scripts should safely create the log directory and an empty logfile.

Example for `postinstall.sh` or `postroot.sh`:

```bash
LOGDIR="$PLOGS"
LOGFILE="$LOGDIR/myplugin.log"
mkdir -p "$LOGDIR"
if [ ! -f "$LOGFILE" ]; then
    touch "$LOGFILE" || true
fi
chmod 664 "$LOGFILE" 2>/dev/null || true
```

If the script runs as root, owner and group can also be set:

```bash
chown loxberry:loxberry "$LOGFILE" 2>/dev/null || true
```

The template files `postinstall.sh` and `postroot.sh` already include this principle.

## Ensure the logfile before showing it

If the web frontend has a **Show log** button, it should not blindly link to the LoxBerry Log Viewer if the logfile may be missing.

A better flow is:

1. Check or create the log directory.
2. Check or create the logfile.
3. Redirect to the LoxBerry Log Viewer.

The examples `index.cgi` and `index.php` therefore use their own `showlog` parameter:

```text
index.cgi?showlog=daemon
index.php?showlog=daemon
```

The frontend ensures that the logfile exists and then redirects to the LoxBerry Log Viewer.

## Long-running Python services

For long-running Python services, avoid a simple `FileHandler` if the logfile can be externally deleted or rotated.

Use `WatchedFileHandler`:

```python
from logging.handlers import WatchedFileHandler
handler = WatchedFileHandler(
    "/opt/loxberry/log/plugins/myplugin/myplugin.log",
    encoding="utf-8",
    delay=True,
)
```

`WatchedFileHandler` helps when a logfile was externally deleted or rotated. The file is reopened on the next log entry.

The example is available here:

```text
examples/python_logging_watchedfilehandler.py
```

## Deleted logfile warning

If a logfile is deleted while a process still has it open, the process may continue writing to a deleted file handle.

Check:

```bash
PID=$(systemctl show -p MainPID --value myplugin.service)
sudo ls -l /proc/$PID/fd | grep -i deleted
```

A service restart helps short-term. Long-term, use a robust log handler and ensure the logfile before opening the log view.

## LoxBerry session logs

For web frontend actions, installation steps and script calls, LoxBerry session logs are often better than a single permanent logfile.

Typical use cases:

- save configuration
- start, stop or restart a service
- export functions
- installation and update scripts
- diagnostic actions

A long-running daemon can additionally keep its own daemon log.

## Recommendations for new plugins

- Create log directory and logfile during installation.
- Ensure the logfile before opening it from the web frontend.
- Use `WatchedFileHandler` for Python daemons.
- Use LoxBerry session logs for web frontend and script actions.
- Avoid uncontrolled logfile growth.
- Avoid generic helper functions such as `trim()`. Prefer plugin-specific names, for example `myplugin_trim()`.
- Regularly test logfiles and log links with the LoxBerry Log Manager.

## Check commands

```bash
ls -lh /opt/loxberry/log/plugins/<pluginname>/
PID=$(systemctl show -p MainPID --value <service>.service)
sudo ls -l /proc/$PID/fd | grep -i deleted
journalctl -u <service>.service --since "10 minutes ago" --no-pager
perl -c /opt/loxberry/webfrontend/htmlauth/plugins/<pluginname>/index.cgi
python3 -m py_compile /opt/loxberry/bin/plugins/<pluginname>/<daemon>.py
```
