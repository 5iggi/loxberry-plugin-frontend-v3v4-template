#!/bin/bash
set -euo pipefail
ROOT="${1:-.}"
CSS="$ROOT/webfrontend/html/css/plugin.css"
for TEMPLATE in "$ROOT/templates/index.html" "$ROOT/templates/index_php.html"; do
  [ -f "$TEMPLATE" ] || continue
  grep -q "plugin-page" "$TEMPLATE"
  grep -q "data-role=\"none\"" "$TEMPLATE"
  grep -q "plugin-button" "$TEMPLATE"
  grep -q "pi pi-" "$TEMPLATE"
done
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
[ -f "$ROOT/webfrontend/htmlauth/index.cgi" ] && perl -c "$ROOT/webfrontend/htmlauth/index.cgi" || true
if command -v php >/dev/null 2>&1 && [ -f "$ROOT/webfrontend/htmlauth/index.php" ]; then php -l "$ROOT/webfrontend/htmlauth/index.php"; fi
echo "OK"
