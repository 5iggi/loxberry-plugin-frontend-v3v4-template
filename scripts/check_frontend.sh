#!/bin/bash
set -euo pipefail
ROOT="${1:-.}"
CSS="$ROOT/webfrontend/html/css/plugin.css"
TEMPLATE_PERL="$ROOT/templates/index.html"
TEMPLATE_PHP="$ROOT/templates/index_php.html"
CGI="$ROOT/webfrontend/htmlauth/index.cgi"
PHP="$ROOT/webfrontend/htmlauth/index.php"

echo "Checking frontend templates..."
for TEMPLATE in "$TEMPLATE_PERL" "$TEMPLATE_PHP"; do
  [ -f "$TEMPLATE" ] || continue
  grep -q "plugin-page" "$TEMPLATE"
  grep -q "data-role=\"none\"" "$TEMPLATE"
  grep -q "plugin-button" "$TEMPLATE"
  grep -q "pi pi-" "$TEMPLATE" || true
done

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

echo "Checking CGI syntax if Perl modules are available..."
[ -f "$CGI" ] && perl -c "$CGI" || true

echo "Checking PHP syntax if php-cli is available..."
if command -v php >/dev/null 2>&1 && [ -f "$PHP" ]; then
  php -l "$PHP"
else
  echo "php CLI not available or PHP file missing, skipped."
fi

echo "Checking PHP template references..."
if [ -f "$PHP" ]; then
  grep -q "LBSystem::readlanguage" "$PHP"
  grep -q "LBWeb::lbheader" "$PHP"
fi

echo "OK"
