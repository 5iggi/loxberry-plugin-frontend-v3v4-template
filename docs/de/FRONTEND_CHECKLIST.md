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

## Release-Test

- ZIP mit `unzip -t` prüfen.
- Shell-Skripte mit `bash -n` prüfen.
- Installation auf LoxBerry v3 testen.
- Installation auf LoxBerry v4 testen.
- Browser-Cache hart neu laden.
