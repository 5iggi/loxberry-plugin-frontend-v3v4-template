#!/bin/bash
set -euo pipefail
ROOT="${1:-.}"

grep -q "plugin-page" "$ROOT/templates/index.html"
grep -q "data-role=\"none\"" "$ROOT/templates/index.html"
grep -q "pi pi-" "$ROOT/templates/index.html"

python3 - <<'PYCHK'
from pathlib import Path
import os
root = os.environ.get('ROOT', '.')
css = Path(root + "/webfrontend/html/css/plugin.css").read_text(encoding="utf-8")
checks = {
    "brace_balance": css.count("{") == css.count("}"),
    "has_scope": ".plugin-page" in css,
    "has_primary": "--lb-btn-primary-bg" in css,
    "has_danger": "--lb-danger" in css,
    "has_loglist_header": ".plugin-loglist .ui-collapsible-heading-toggle" in css,
    "has_logviewer_button": "href*=\"logfile.cgi\"" in css,
}
print(checks)
if not all(checks.values()):
    raise SystemExit(1)
PYCHK

echo "OK"
