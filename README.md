# LoxBerry Plugin Frontend v3/v4 Template

Reusable neutral frontend template for LoxBerry plugins that should look consistent on LoxBerry v3 and LoxBerry v4.

Wiederverwendbare neutrale Frontend-Vorlage fuer LoxBerry-Plugins, die unter LoxBerry v3 und LoxBerry v4 moeglichst einheitlich aussehen sollen.

## Documentation / Dokumentation

- Deutsch: [docs/de/README.md](docs/de/README.md)
- English: [docs/en/README.md](docs/en/README.md)

## Repository purpose

This repository provides a small, neutral starting point for LoxBerry plugin frontends:

- scoped CSS for plugin pages
- v4-style CSS variables with v3 fallbacks
- jQuery Mobile compatibility guards
- `data-role="none"` examples for custom buttons
- CSS loading via `$htmlhead`
- cache buster example
- Python daemon logging example with `WatchedFileHandler`
- frontend release checklist

## Quick start

1. Replace `PLUGINNAME` with your plugin folder/name.
2. Adjust `plugin.cfg`.
3. Copy or adapt `templates/index.html` and `webfrontend/html/css/plugin.css`.
4. Load your CSS from `index.cgi` via `$htmlhead` before calling `lbheader()`.
5. Run `scripts/check_frontend.sh .`.

## License

MIT License. See [LICENSE](LICENSE).
