# LoxBerry Plugin Frontend v3/v4 Template

Reusable neutral frontend template for LoxBerry plugins that should look consistent on LoxBerry v3 and LoxBerry v4.

Wiederverwendbare neutrale Frontend-Vorlage für LoxBerry-Plugins, die unter LoxBerry v3 und LoxBerry v4 möglichst einheitlich aussehen sollen.

## Documentation / Dokumentation

- Deutsch: [docs/de/README.md](docs/de/README.md)
- English: [docs/en/README.md](docs/en/README.md)

## Repository purpose

This repository provides a small, neutral starting point for LoxBerry plugin frontends:

- scoped CSS for plugin pages
- v4-style CSS variables with v3 fallbacks
- jQuery Mobile compatibility guards
- `data-role="none"` examples for custom buttons and form controls
- CSS loading via `$htmlhead`
- cache buster example
- Perl CGI frontend example
- PHP frontend example using `loxberry_web.php`, `loxberry_system.php` and language files
- Python daemon logging example with `WatchedFileHandler`
- frontend release checklist

## Quick start

1. Replace `PLUGINNAME` with your plugin folder/name.
2. Adjust `plugin.cfg`.
3. Choose one frontend variant:
   - Perl CGI: `webfrontend/htmlauth/index.cgi` with `templates/index.html`
   - PHP: `webfrontend/htmlauth/index.php` with `templates/index_php.html`
4. Adapt `webfrontend/html/css/plugin.css`.
5. Load your CSS from the frontend via `$htmlhead` before calling `lbheader()`.
6. Run the frontend check:

```bash
chmod +x scripts/check_frontend.sh
./scripts/check_frontend.sh .
```

The script checks template structure, scoped CSS, common jQuery Mobile artifacts and Perl/PHP syntax where possible.

## Files to reuse

- `webfrontend/html/css/plugin.css` - CSS base structure with a plugin scope, tokens and fallbacks
- `templates/index.html` - Perl/HTML::Template example with `data-role="none"`
- `templates/index_php.html` - PHP template example with v3/v4-compatible markup
- `webfrontend/htmlauth/index.cgi` - minimal Perl CGI with CSS injection via `$htmlhead`
- `webfrontend/htmlauth/index.php` - PHP frontend example using LoxBerry PHP SDK modules
- `templates/lang/language_de.ini` and `templates/lang/language_en.ini` - language examples
- `examples/python_logging_watchedfilehandler.py` - robust Python daemon logging example
- `scripts/check_frontend.sh` - checks for common frontend mistakes
- `docs/de` and `docs/en` - bilingual notes and checklists

## License

MIT License. See [LICENSE](LICENSE).
