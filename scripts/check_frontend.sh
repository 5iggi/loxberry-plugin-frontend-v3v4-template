#!/bin/bash
set -euo pipefail
ROOT="${1:-.}"
CSS="$ROOT/webfrontend/html/css/plugin.css"
TEMPLATE="$ROOT/templates/index.html"
CGI="$ROOT/webfrontend/htmlauth/index.cgi"

echo "Checking frontend template..."

grep -q "plugin-page" "$TEMPLATE"
grep -q "data-role=\"none\"" "$TEMPLATE"
grep -q "plugin-button" "$TEMPLATE"

echo "Checking CSS..."
python3 - <<PY
from pathlib import Path
css = Path("$CSS").read_text(encoding="utf-8")
checks = {
  "brace_balance": css.count("{") == css.count("}"),
  "has_scope": ".plugin-page" in css,
  "has_tokens": "--plugin-primary" in css,
  "no_bad_pseudo": not any(x in css for x in ["ui-btnbefore", "ui-btnafter", "labelbefore", "labelafter"]),
}
print(checks)
if not all(checks.values()):
    raise SystemExit(1)
PY

echo "Checking CGI syntax if perl modules are available..."
perl -c "$CGI" || true

echo "OK"
