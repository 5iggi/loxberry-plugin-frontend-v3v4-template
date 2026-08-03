# LoxBerry Plugin Frontend v3/v4 Template

## Ziel

Diese Vorlage hilft beim Aufbau eines neutralen LoxBerry-Plugin-Frontends, das sowohl unter LoxBerry v3 mit jQuery Mobile als auch unter LoxBerry v4 mit modernen CSS-Tokens stabil dargestellt wird.

Die Vorlage ist bewusst kein vollstaendiges Plugin. Sie enthaelt die wiederverwendbaren Bausteine fuer neue Plugins.

## Was kann uebernommen werden?

- `webfrontend/html/css/plugin.css`: CSS-Grundstruktur mit eigenem Scope, Tokens und Fallbacks.
- `templates/index.html`: Beispiel-Template mit `data-role="none"` fuer eigene Buttons.
- `webfrontend/htmlauth/index.cgi`: minimales Perl-CGI mit CSS-Einbindung ueber `$htmlhead`.
- `examples/python_logging_watchedfilehandler.py`: Beispiel fuer robustes Python-Daemon-Logging.
- `scripts/check_frontend.sh`: einfacher Check fuer typische Frontend-Fehler.
- `docs/de` und `docs/en`: zweisprachige Hinweise und Checklisten.

## Grundprinzipien

1. Ein eindeutiger Wrapper kapselt das gesamte Plugin-Frontend.
2. Eigene CSS-Variablen nutzen LoxBerry-v4-Tokens, bieten aber Fallbacks fuer v3.
3. Eigene Buttons bekommen `data-role="none"`, damit jQuery Mobile diese Buttons nicht umgestaltet.
4. CSS wird ueber `$htmlhead` geladen und mit einem Cachebuster versehen.
5. Generische Funktionsnamen wie `trim()` werden vermieden. Nutze Plugin-Praefixe.
6. Daemon-Logs sollten robust gegen externe Logrotation oder externe Loeschung sein.

## Schnellstart

```bash
git clone https://github.com/YOURUSER/loxberry-plugin-frontend-v3v4-template.git
cd loxberry-plugin-frontend-v3v4-template
./scripts/check_frontend.sh .
```

Danach `PLUGINNAME` in allen Dateien ersetzen.

## Empfohlener Repository-Name

```text
loxberry-plugin-frontend-v3v4-template
```

Alternativen:

```text
loxberry-frontend-compat-template
loxberry-plugin-ui-template
loxberry-v3v4-css-template
```
