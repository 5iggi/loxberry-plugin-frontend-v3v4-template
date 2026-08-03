#!/bin/bash
set -u

PLUGIN="${3:-PLUGINNAME}"
BASE="${LBHOMEDIR:-/opt/loxberry}"
LOGDIR="${LBPLOG:-$BASE/log/plugins}/$PLUGIN"
LOGFILE="$LOGDIR/$PLUGIN.log"

mkdir -p "$LOGDIR" || true
if [ ! -f "$LOGFILE" ]; then
    touch "$LOGFILE" || true
fi
chmod 664 "$LOGFILE" 2>/dev/null || true

exit 0
