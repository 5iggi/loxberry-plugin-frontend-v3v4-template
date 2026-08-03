# LoxBerry Plugin Frontend v3/v4 Template

## Goal

This template helps to build a neutral LoxBerry plugin frontend that works consistently on LoxBerry v3 with jQuery Mobile and LoxBerry v4 with modern CSS tokens.

## What can be reused?

- `webfrontend/html/css/plugin.css`: CSS base structure with scope, tokens and fallbacks.
- `templates/index.html`: Perl/HTML::Template example with `data-role="none"` and PrimeIcons.
- `templates/index_php.html`: PHP template with v3/v4-compatible markup and PrimeIcons.
- `webfrontend/htmlauth/index.cgi`: Perl CGI with robust template path lookup.
- `webfrontend/htmlauth/index.php`: PHP example using LoxBerry PHP SDK, language files and a dynamic CSS path.
- `examples/python_logging_watchedfilehandler.py`: optional example for robust Python daemon logging.
- `scripts/check_frontend.sh`: check script for common frontend mistakes.

## More documents

- [CSS Guide](CSS_GUIDE.md)
- [PHP frontend template](PHP_TEMPLATE.md)
- [Frontend Checklist](FRONTEND_CHECKLIST.md)
- [Logging Notes](LOGGING_NOTES.md)

## Quick start

```bash
git clone https://github.com/5iggi/loxberry-plugin-frontend-v3v4-template.git
cd loxberry-plugin-frontend-v3v4-template
chmod +x scripts/check_frontend.sh
./scripts/check_frontend.sh .
```

Then replace `PLUGINNAME` in all files.
