# Logging Notes

These notes summarize robust, LoxBerry-friendly logging patterns for plugins.

## LoxBerry concept

LoxBerry provides its own Logging SDK. It is especially useful for web frontend actions, installation steps, script calls and diagnostics. LoxBerry logging is session-based: each script run creates its own log session that is small, readable and easy to identify in the LoxBerry Log Manager.

For long-running services, for example Python daemons, an additional persistent daemon log may still be useful.

## Recommended separation

```text
Web frontend action   -> LoxBerry Logging SDK / session log
Installation/upgrade  -> LoxBerry Logging SDK / session log or installation log
Long-running daemon   -> own daemon log with robust log handler
Demo/plugin main log  -> /opt/loxberry/log/plugins/<plugin>/<plugin>.log
```

## Standard logfile in this template

The template does not use a fixed `daemon.log` for the demo log button. It uses the plugin main logfile:

```text
/opt/loxberry/log/plugins/<plugin>/<plugin>.log
```

The template button does not link directly to the Log Viewer. It first calls the plugin frontend:

```text
index.cgi?showlog=plugin
index.php?showlog=plugin
```

The frontend ensures the log directory and logfile exist and then redirects to the LoxBerry Log Viewer.

## Create logfile during installation

The example scripts `postinstall.sh` and `postroot.sh` create the demo/plugin main logfile:

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

When the script runs as root, owner and group can also be set:

```bash
chown loxberry:loxberry "$LOGFILE" 2>/dev/null || true
```

## Web frontend actions

For real actions such as saving, restarting, exporting, diagnostics or installation steps, prefer the LoxBerry Logging SDK. A single permanent logfile for all actions is not the core idea of LoxBerry logging.

## Long-running Python services

For Python daemons, `WatchedFileHandler` is useful because logfiles may be externally deleted or rotated:

```python
from logging.handlers import WatchedFileHandler
handler = WatchedFileHandler(
    "/opt/loxberry/log/plugins/myplugin/myplugin.log",
    encoding="utf-8",
    delay=True,
)
```

The example is here:

```text
examples/python_logging_watchedfilehandler.py
```

## Check deleted logfiles

If a logfile is deleted while a process still has it open, the process may continue writing to a deleted file handle.

```bash
PID=$(systemctl show -p MainPID --value myplugin.service)
sudo ls -l /proc/$PID/fd | grep -i deleted
```

A service restart helps short-term. Long-term, use a robust log handler.

## Check commands

```bash
ls -lh /opt/loxberry/log/plugins/<pluginname>/
perl -c /opt/loxberry/webfrontend/htmlauth/plugins/<pluginname>/index.cgi
php -l /opt/loxberry/webfrontend/htmlauth/plugins/<pluginname>/index.php
python3 -m py_compile /opt/loxberry/bin/plugins/<pluginname>/<daemon>.py
```
