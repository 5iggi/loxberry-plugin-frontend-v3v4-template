# LoxBerry Plugin Frontend v3/v4 Template

Reusable neutral frontend template for LoxBerry plugins that should look consistent on LoxBerry v3 and LoxBerry v4.

Wiederverwendbare neutrale Frontend-Vorlage für LoxBerry-Plugins, die unter LoxBerry v3 und LoxBerry v4 möglichst einheitlich aussehen sollen.

## Documentation / Dokumentation

- Deutsch: [docs/de/README.md](docs/de/README.md)
- English: [docs/en/README.md](docs/en/README.md)
- Sources / Quellen: [docs/SOURCES.md](docs/SOURCES.md)

## Repository purpose

This repository provides a neutral starting point for LoxBerry plugin frontends:

- scoped CSS for plugin pages
- LoxBerry-v4-style CSS variables with fallbacks for LoxBerry v3
- jQuery Mobile compatibility guards
- `data-role="none"` examples for custom controls
- CSS loading through `$htmlhead`
- Perl CGI and PHP frontend examples
- horizontal LoxBerry navbar examples
- PrimeIcons in own buttons
- scoped styling for LoxBerry-generated log lists
- LoxBerry Logging SDK examples and notes
- frontend release checklist

## Quick start

1. Replace `PLUGINNAME` with your plugin folder/name.
2. Adjust `plugin.cfg`.
3. Choose one frontend variant:
   - Perl CGI: `webfrontend/htmlauth/index.cgi` with `templates/index.html`
   - PHP: `webfrontend/htmlauth/index.php` with `templates/index_php.html`
4. Adapt `webfrontend/html/css/plugin.css`.
5. Load the CSS via `$htmlhead` before calling `lbheader()`.
6. Run the frontend check:

```bash
chmod +x scripts/check_frontend.sh
./scripts/check_frontend.sh .
```

## Files to reuse

- `webfrontend/html/css/plugin.css`
- `templates/index.html`
- `templates/index_php.html`
- `webfrontend/htmlauth/index.cgi`
- `webfrontend/htmlauth/index.php`
- `templates/lang/language_de.ini`
- `templates/lang/language_en.ini`
- `bin/create_demo_log.sh`
- `examples/python_logging_watchedfilehandler.py`
- `scripts/check_frontend.sh`

## License

MIT License. See [LICENSE](LICENSE).
