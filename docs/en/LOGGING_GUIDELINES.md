# Logging Guidelines for LoxBerry Plugins

## Goal

These notes summarize neutral recommendations for robust logging in LoxBerry plugins. They specifically consider that LoxBerry actively maintains and cleans log files.

## Log directory

Plugin logs generally belong to:

```text
/opt/loxberry/log/plugins/<pluginname>/
```

The plugin may create the directory during installation or at runtime. However, a plugin should not assume that files stored there are preserved forever.

## LoxBerry cleans up logs

LoxBerry may automatically truncate or delete log files when:

- log files are old,
- log files are very large,
- the log area or tmpfs/RAM disk is low on free space.

The Log Manager's "Logfiles" area mainly shows logging sessions. This is not the same as a raw file list. A session may remain visible even if the underlying file has already been truncated or deleted.

## Log database

LoxBerry keeps a log database. This database is used by log lists and the Log Manager. If the database cannot be written, for example because tmpfs is full, log creation may fail.

Checks:

```bash
df -h /tmp /opt/loxberry/log 2>/dev/null
ls -lh /opt/loxberry/log/plugins/<pluginname>/
```

## Session logs

For actions, prefer session logs through the LoxBerry Logging SDK.

Bash example:

```bash
. "$LBHOMEDIR/libs/bashlib/loxberry_log.sh"
PACKAGE="$PLUGIN"
NAME="action"
FILENAME="$LBPLOG/$PLUGIN/action.log"
APPEND=1
LOGSTART "Action started."
LOGINF "Doing something."
LOGOK "Action finished."
LOGEND "Action finished."
```

Important:

- `PACKAGE` matches the plugin folder.
- `NAME` is the log group.
- `FILENAME` must be set.
- `LOGEND` should be used for completed actions.

## Daemon logs

A daemon may write its own persistent log file. This file may still be deleted or rotated by LoxBerry or external maintenance.

Recommendations:

- The daemon can ensure the log directory at startup.
- The daemon should be robust against deleted or rotated log files.
- A service restart should be able to recreate the log file.
- Logs should not be used as state storage.

## Python daemons

For Python daemons, `WatchedFileHandler` is a robust option:

```python
from logging.handlers import WatchedFileHandler
handler = WatchedFileHandler(
    "/opt/loxberry/log/plugins/myplugin/myplugin.log",
    encoding="utf-8",
    delay=True,
)
```

The handler can detect when a log file has been replaced or rotated externally and reopens the file.

## Deleted open log files

If a process keeps a log file open and the file is deleted outside the process, the process can continue writing to a deleted file handle. The file is no longer visible in the directory but can still consume disk space.

Check:

```bash
PID=$(systemctl show -p MainPID --value <service>.service)
sudo ls -l /proc/$PID/fd | grep -i deleted
```

A service restart helps in the short term. Long term, the service should be able to reopen the log file robustly.

## Recommendations

- Use logs for diagnostics, not as a database.
- Record actions as LoxBerry session logs.
- Keep daemon logs small and able to reopen after rotation/deletion.
- Use LoxBerry functions or the Log Viewer for log display.
- Do not rely on manually copied log files for `loglist_html()`.
- Test log behavior on both LoxBerry v3 and v4.
