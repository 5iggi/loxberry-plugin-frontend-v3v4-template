# LoxBerry Plugin Frontend v3/v4 Template

## Ziel

Diese Vorlage hilft beim Aufbau eines neutralen LoxBerry-Plugin-Frontends, das sowohl unter LoxBerry v3 mit jQuery Mobile als auch unter LoxBerry v4 mit modernen CSS-Tokens stabil dargestellt wird.

## Was kann übernommen werden?

- `webfrontend/html/css/plugin.css`: CSS-Grundstruktur mit eigenem Scope, Tokens und Fallbacks.
- `templates/index.html`: Perl/HTML::Template Beispiel mit `data-role="none"` und PrimeIcons.
- `templates/index_php.html`: PHP-Template mit v3/v4-kompatiblem Markup und PrimeIcons.
- `webfrontend/htmlauth/index.cgi`: Perl-CGI mit robuster Template-Pfadsuche.
- `webfrontend/htmlauth/index.php`: PHP-Beispiel mit LoxBerry PHP SDK, Sprachdateien und dynamischem CSS-Pfad.
- `examples/python_logging_watchedfilehandler.py`: optionales Beispiel für robustes Python-Daemon-Logging.
- `scripts/check_frontend.sh`: Check für typische Frontend-Fehler.

## Weitere Dokumente

- [CSS-Leitfaden](CSS_GUIDE.md)
- [PHP-Frontend-Vorlage](PHP_TEMPLATE.md)
- [Frontend-Checkliste](FRONTEND_CHECKLIST.md)
- [Logging-Hinweise](LOGGING_NOTES.md)

## Schnellstart

```bash
git clone https://github.com/5iggi/loxberry-plugin-frontend-v3v4-template.git
cd loxberry-plugin-frontend-v3v4-template
chmod +x scripts/check_frontend.sh
./scripts/check_frontend.sh .
```

Danach `PLUGINNAME` in allen Dateien ersetzen.
