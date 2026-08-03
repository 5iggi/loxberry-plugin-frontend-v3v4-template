# Frontend-Checkliste

## Template

- [ ] Alle Inhalte liegen in einem eindeutigen Wrapper, z. B. `.plugin-page`.
- [ ] Eigene Buttons und Button-Links haben `data-role="none"`.
- [ ] Wiederkehrende Styles sind in CSS ausgelagert.
- [ ] Links oeffnen nur gezielt neue Tabs oder Fenster.

## CSS

- [ ] CSS-Datei wird ueber `$htmlhead` eingebunden.
- [ ] `$htmlhead` wird vor `lbheader()` gesetzt.
- [ ] Cachebuster wurde erhoeht.
- [ ] CSS-Klammern sind ausgeglichen.
- [ ] Keine Artefakte wie `ui-btnbefore`, `ui-btnafter`, `labelbefore`, `labelafter`.
- [ ] Alle v4-Tokens haben Fallbacks fuer v3.
- [ ] Responsive Darstellung wurde geprueft.

## CGI / Perl

- [ ] Keine generischen Funktionsnamen wie `trim()` verwenden.
- [ ] Besser: `plugin_trim()`, `myplugin_trim()` oder aehnlich.
- [ ] `perl -c index.cgi` auf einem passenden System pruefen.
- [ ] AJAX-Antworten als JSON mit UTF-8 ausgeben.

## Release-Test

- [ ] ZIP mit `unzip -t` pruefen.
- [ ] Shell-Skripte mit `bash -n` pruefen.
- [ ] Installation auf LoxBerry v3 testen.
- [ ] Installation auf LoxBerry v4 testen.
- [ ] Browser-Cache hart neu laden.
