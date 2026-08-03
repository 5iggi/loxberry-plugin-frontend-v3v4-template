# Frontend-Checkliste

## Template

- Alle Inhalte liegen in einem eindeutigen Wrapper, z. B. `.plugin-page`.
- Eigene Buttons und Button-Links haben `data-role="none"`.
- Wiederkehrende Styles sind in CSS ausgelagert.
- Links öffnen nur gezielt neue Tabs oder Fenster.

## CSS

- CSS-Datei wird über `$htmlhead` eingebunden.
- `$htmlhead` wird vor `lbheader()` gesetzt.
- Cachebuster wurde erhöht.
- CSS-Klammern sind ausgeglichen.
- Keine Artefakte wie `ui-btnbefore`, `ui-btnafter`, `labelbefore`, `labelafter`.
- Alle v4-Tokens haben v3-Fallbacks.
- Responsive Darstellung wurde geprüft.

## CGI / Perl

- Keine generischen Funktionsnamen wie `trim()` verwenden.
- Besser: `plugin_trim()`, `myplugin_trim()` oder ähnlich.
- `perl -c index.cgi` auf einem passenden System prüfen.
- AJAX-Antworten als JSON mit UTF-8 ausgeben.

## PHP

- `loxberry_web.php` und `loxberry_system.php` verwenden.
- CSS-Pfad dynamisch über `LBPPLUGINDIR` bauen.
- `LBSystem::readlanguage("language.ini")` für Sprachdateien nutzen.
- PHP-Syntax mit `php -l index.php` prüfen, wenn `php-cli` verfügbar ist.

## Automatischer Frontend-Check

Das Repository enthält ein Hilfsscript:

```bash
scripts/check_frontend.sh
```

Das Script prüft typische Fehler in der Frontend-Struktur, in Templates und in der CSS-Datei.

### Ausführen

Im Repository-Root:

```bash
./scripts/check_frontend.sh .
```

Oder mit einem expliziten Pfad:

```bash
./scripts/check_frontend.sh /pfad/zum/plugin
```

Falls das Script nicht ausführbar ist:

```bash
chmod +x scripts/check_frontend.sh
./scripts/check_frontend.sh .
```

### Was wird geprüft?

Das Script prüft unter anderem:

- ob das Template einen eindeutigen Plugin-Wrapper enthält, z. B. `.plugin-page`
- ob eigene Buttons `data-role="none"` verwenden
- ob eigene Button-Klassen vorhanden sind
- ob die CSS-Datei ausgeglichene `{}` Klammern hat
- ob die CSS-Datei den Plugin-Scope verwendet
- ob CSS-Tokens wie `--plugin-primary` vorhanden sind
- ob typische Copy/Paste-Fehler wie `ui-btnbefore`, `ui-btnafter`, `labelbefore` oder `labelafter` vorkommen
- ob `index.cgi` per `perl -c` geprüft werden kann
- ob `index.php` per `php -l` geprüft werden kann, falls `php-cli` installiert ist

### Hinweis

Der Check ersetzt keinen Test auf einem echten LoxBerry v3 oder v4. Er hilft aber, typische Struktur-, CSS- und Templatefehler vor einem Release früh zu finden.

## Release-Test

- ZIP mit `unzip -t` prüfen.
- Shell-Skripte mit `bash -n` prüfen.
- Installation auf LoxBerry v3 testen.
- Installation auf LoxBerry v4 testen.
- Browser-Cache hart neu laden.
