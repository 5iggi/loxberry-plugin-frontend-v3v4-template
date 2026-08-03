#!/bin/bash
set -u
PLUGIN="${3:-PLUGINNAME}"
BASE="${LBHOMEDIR:-/opt/loxberry}"
LOGDIR="${LBPLOG:-$BASE/log/plugins}/$PLUGIN"
LOGFILE="$LOGDIR/$PLUGIN.log"
mkdir -p "$LOGDIR" || true
[ -f "$LOGFILE" ] || touch "$LOGFILE" || true
chmod 664 "$LOGFILE" 2>/dev/null || true
chown loxberry:loxberry "$LOGFILE" 2>/dev/null || true
exit 0
