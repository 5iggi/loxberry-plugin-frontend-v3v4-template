# LoxBerry Plugin Frontend v3/v4 Template

## Goal

This template helps to build a neutral LoxBerry plugin frontend that works consistently on LoxBerry v3 with jQuery Mobile and LoxBerry v4 with modern CSS tokens.

This repository is intentionally not a complete plugin. It provides reusable building blocks for new plugins.

## What can be reused?

- `webfrontend/html/css/plugin.css`: CSS base structure with scope, tokens and fallbacks.
- `templates/index.html`: example template with `data-role="none"` for custom buttons.
- `webfrontend/htmlauth/index.cgi`: minimal Perl CGI with CSS injection via `$htmlhead`.
- `examples/python_logging_watchedfilehandler.py`: example for robust Python daemon logging.
- `scripts/check_frontend.sh`: simple check script for common frontend mistakes.
- `docs/de` and `docs/en`: bilingual notes and checklists.

## Principles

1. A unique wrapper scopes the complete plugin frontend.
2. Custom CSS variables use LoxBerry v4 tokens and provide fallbacks for v3.
3. Custom buttons use `data-role="none"` to avoid jQuery Mobile enhancement.
4. CSS is loaded via `$htmlhead` and uses a cache buster.
5. Generic helper names like `trim()` are avoided. Use plugin prefixes.
6. Daemon logs should be robust against external log rotation or deletion.

## Quick start

```bash
git clone https://github.com/5iggi/loxberry-plugin-frontend-v3v4-template.git
cd loxberry-plugin-frontend-v3v4-template
./scripts/check_frontend.sh .
```

Then replace `PLUGINNAME` in all files.

## Recommended repository name

```text
loxberry-plugin-frontend-v3v4-template
```

Alternatives:

```text
loxberry-frontend-compat-template
loxberry-plugin-ui-template
loxberry-v3v4-css-template
```
