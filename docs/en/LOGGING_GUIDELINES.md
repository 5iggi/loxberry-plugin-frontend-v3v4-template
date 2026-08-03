# Logging Guidelines for LoxBerry Plugins

These notes summarize general recommendations for future LoxBerry plugins. The goal is robust logging that works reliably with the LoxBerry Log Manager and long-running services.

## Principles

- Plugin logs should be stored in the dedicated plugin log directory:

```text
/opt/loxberry/log/plugins/<pluginname>/
```

- The plugin should create the log directory during installation and, if needed, at runtime.
- A log file should not only appear after the first real event. It should already exist after installation or before the log view is opened.
- Long-running services must handle externally deleted or rotated log files.
- Web frontend logs, installation logs and daemon logs should be treated separately where possible.

## Recommended Structure

```text
/opt/loxberry/log/plugins/<pluginname>/<pluginname>.log
/opt/loxberry/log/plugins/<pluginname>/<additional-session-logs>.log
```

Example:

```text
/opt/loxberry/log/plugins/myplugin/myplugin.log
```

## Installation and Update

Installation or root scripts should create the log directory and an empty log file.

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

If the script runs as `root`, owner and group can also be adjusted:

```bash
chown loxberry:loxberry "$LOGFILE" 2>/dev/null || true
```

## Ensure the Log File Before Opening It

If the web frontend has a “Show log” button, do not blindly link directly to the LoxBerry Log Viewer if the file may be missing.

A safer approach is a small step in `index.cgi`:

1. Check or create the log directory.
2. Check or create the log file.
3. Redirect to the LoxBerry Log Viewer afterwards.

Example principle:

```perl
if ($cgi->param('showlog')) {
    my $logdir  = '/opt/loxberry/log/plugins/myplugin';
    my $logfile = "$logdir/myplugin.log";

    mkdir $logdir if !-d $logdir;

    if (!-e $logfile) {
        open my $fh, '>>', $logfile;
        close $fh if $fh;
        chmod 0664, $logfile;
    }

    print $cgi->redirect('/admin/system/tools/logfile.cgi?logfile=plugins/myplugin/myplugin.log&header=html&format=template');
    exit;
}
```

The template button should then point to the plugin CGI:

```html
<a href="index.cgi?showlog=daemon">Show log</a>
```

## Long-Running Python Services

For long-running Python services, avoid a plain `FileHandler` if the log file may be externally deleted or rotated.

Use `WatchedFileHandler` instead:

```python
import logging
from logging.handlers import WatchedFileHandler

handler = WatchedFileHandler(
    "/opt/loxberry/log/plugins/myplugin/myplugin.log",
    encoding="utf-8",
    delay=True,
)

formatter = logging.Formatter("%(asctime)s %(levelname)s: %(message)s")
handler.setFormatter(formatter)

root = logging.getLogger()
root.addHandler(handler)
root.setLevel(logging.INFO)
```

`WatchedFileHandler` helps when a log file has been deleted or rotated externally. On the next log entry, the file is reopened.

## Important Note About Deleted Log Files

If a log file is deleted while a process still has it open, the process can continue writing to a deleted file handle. This may look like this:

```text
/opt/loxberry/log/plugins/myplugin/myplugin.log (deleted)
```

Check with:

```bash
PID=$(systemctl show -p MainPID --value myplugin.service)
sudo ls -l /proc/$PID/fd | grep -i deleted
```

A service restart helps in the short term. Long term, the application should use a robust log handler and ensure the log file before it is opened through the web frontend.

## LoxBerry Session Logs

For web frontend actions, installation steps and script calls, LoxBerry session logs are often better suited than a single permanent log file.

Typical use cases:

- saving configuration
- starting, stopping or restarting a service
- export functions
- installation and update scripts
- diagnostics

For these actions, the LoxBerry Logging SDK can be used. A long-running daemon may still keep its own daemon log in addition.

## Recommendations for New Plugins

- Create the log directory and log file during installation.
- Ensure the log file before opening it in the web frontend.
- Use `WatchedFileHandler` for Python daemons.
- Use LoxBerry session logs for web frontend and script actions.
- Avoid uncontrolled log file growth.
- Avoid generic helper function names like `trim()` in CGI code if name collisions are possible. Prefer plugin-specific names, e.g. `myplugin_trim()`.
- Regularly test log files and log links with the LoxBerry Log Manager.

## Check Commands

```bash
ls -lh /opt/loxberry/log/plugins/<pluginname>/
```

```bash
PID=$(systemctl show -p MainPID --value <service>.service)
sudo ls -l /proc/$PID/fd | grep -i deleted
```

```bash
journalctl -u <service>.service --since "10 minutes ago" --no-pager
```

```bash
perl -c /opt/loxberry/webfrontend/htmlauth/plugins/<pluginname>/index.cgi
```

```bash
python3 -m py_compile /opt/loxberry/bin/plugins/<pluginname>/<daemon>.py
```

## Summary

For robust LoxBerry plugin logging, a plugin should not only write a log file, but also handle its lifecycle:

- create it during installation,
- ensure it before displaying it,
- handle external deletion or rotation in daemons,
- log web frontend actions separately as LoxBerry session logs.
