#!/bin/bash
set -euo pipefail
ROOT="${1:-.}"
CSS="$ROOT/webfrontend/html/css/plugin.css"
TEMPLATE_PERL="$ROOT/templates/index.html"
TEMPLATE_PHP="$ROOT/templates/index_php.html"
CGI="$ROOT/webfrontend/htmlauth/index.cgi"
PHP="$ROOT/webfrontend/htmlauth/index.php"

echo "Checking frontend template..."
if [ -f "$TEMPLATE_PERL" ]; then
  grep -q "plugin-page" "$TEMPLATE_PERL"
  grep -q "data-role=\"none\"" "$TEMPLATE_PERL"
  grep -q "plugin-button" "$TEMPLATE_PERL"
fi
if [ -f "$TEMPLATE_PHP" ]; then
  grep -q "plugin-page" "$TEMPLATE_PHP"
  grep -q "data-role=\"none\"" "$TEMPLATE_PHP"
  grep -q "plugin-button" "$TEMPLATE_PHP"
fi

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
  grep -q "LBPPLUGINDIR" "$PHP"
fi

echo "OK"
