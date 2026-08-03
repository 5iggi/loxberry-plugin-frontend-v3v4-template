# LoxBerry Plugin Frontend v3/v4 Template

## Ziel

Diese Vorlage hilft beim Aufbau eines neutralen LoxBerry-Plugin-Frontends, das sowohl unter LoxBerry v3 mit jQuery Mobile als auch unter LoxBerry v4 mit modernen CSS-Tokens stabil dargestellt wird.

Die Vorlage ist bewusst kein vollständiges Plugin. Sie enthält wiederverwendbare Bausteine für neue Plugins.

## Was kann übernommen werden?

- `webfrontend/html/css/plugin.css`: CSS-Grundstruktur mit eigenem Scope, Tokens und Fallbacks.
- `templates/index.html`: Perl/HTML::Template Beispiel mit `data-role="none"` für eigene Buttons.
- `templates/index_php.html`: PHP-Template mit v3/v4-kompatiblem Markup.
- `webfrontend/htmlauth/index.cgi`: minimales Perl-CGI mit CSS-Einbindung über `$htmlhead`.
- `webfrontend/htmlauth/index.php`: PHP-Beispiel mit LoxBerry PHP SDK, Sprachdateien und dynamischem CSS-Pfad.
- `examples/python_logging_watchedfilehandler.py`: Beispiel für robustes Python-Daemon-Logging.
- `scripts/check_frontend.sh`: einfacher Check für typische Frontend-Fehler.
- `docs/de` und `docs/en`: zweisprachige Hinweise und Checklisten.

## Grundprinzipien

- Ein eindeutiger Wrapper kapselt das gesamte Plugin-Frontend.
- Eigene CSS-Variablen nutzen LoxBerry-v4-Tokens, bieten aber Fallbacks für v3.
- Eigene Buttons bekommen `data-role="none"`, damit jQuery Mobile diese Buttons nicht umgestaltet.
- CSS wird über `$htmlhead` geladen und mit einem Cachebuster versehen.
- Generische Funktionsnamen wie `trim()` werden vermieden. Nutze Plugin-Präfixe.
- Daemon-Logs sollten robust gegen externe Logrotation oder externe Löschung sein.

## Weitere Dokumente

- [CSS-Leitfaden](CSS_GUIDE.md)
- [PHP-Frontend-Vorlage](PHP_TEMPLATE.md)
- [Frontend-Checkliste](FRONTEND_CHECKLIST.md)
- [Logging-Hinweise](LOGGING_NOTES.md)

## Schnellstart

```bash
git clone https://github.com/5iggi/loxberry-plugin-frontend-v3v4-template.git
cd loxberry-plugin-frontend-v3v4-template
./scripts/check_frontend.sh .
```

Danach `PLUGINNAME` in allen Dateien ersetzen.
