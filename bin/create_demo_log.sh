#!/bin/bash

# -----------------------------------------------------------------------------
# Demo log creator for the LoxBerry frontend template.
# -----------------------------------------------------------------------------
# This script intentionally uses the LoxBerry Bash Logging SDK. The generated
# log can then be shown by LoxBerry loglist_html(). Do not use "set -u" here,
# because loxberry_log.sh may evaluate optional variables.
# -----------------------------------------------------------------------------

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
PLUGIN="${LBPPLUGINDIR:-$(basename "$SCRIPT_DIR")}" 
LBHOMEDIR="${LBHOMEDIR:-/opt/loxberry}"
LBPLOG="${LBPLOG:-/opt/loxberry/log/plugins}"
LOGDIR_PLUGIN="$LBPLOG/$PLUGIN"
LOG_LIB="$LBHOMEDIR/libs/bashlib/loxberry_log.sh"

if [ ! -r "$LOG_LIB" ]; then
  echo "LoxBerry log library not found: $LOG_LIB" >&2
  exit 1
fi

mkdir -p "$LOGDIR_PLUGIN"

# Variables expected by the LoxBerry Bash logging library.
PACKAGE="$PLUGIN"
NAME="demo"
LOGDIR=""
FILENAME="$LOGDIR_PLUGIN/demo.log"
APPEND=1
STDERR=1
LOGLEVEL=7

. "$LOG_LIB"

# Set again after sourcing in case the library initialized defaults.
PACKAGE="$PLUGIN"
NAME="demo"
LOGDIR=""
FILENAME="$LOGDIR_PLUGIN/demo.log"
APPEND=1
STDERR=1
LOGLEVEL=7

LOGSTART "Demo log started."
LOGINF "This log was created by the LoxBerry Bash Logging SDK."
LOGOK "Demo log finished successfully."
LOGEND "Demo log finished."

chmod 664 "$FILENAME" 2>/dev/null || true
chown loxberry:loxberry "$FILENAME" 2>/dev/null || true

exit 0
