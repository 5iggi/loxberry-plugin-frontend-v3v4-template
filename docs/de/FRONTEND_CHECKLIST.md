# Frontend-Checkliste

## Template

- Alle Inhalte liegen in einem eindeutigen Wrapper, z. B. `.plugin-page`.
- Eigene Buttons und Button-Links haben `data-role="none"`.
- Wiederkehrende Styles sind in CSS ausgelagert.
- Links öffnen nur gezielt neue Tabs oder Fenster.
- Loglink über `index.cgi?showlog=plugin` oder `index.php?showlog=plugin` prüfen.
- Demo-Buttons verwenden PrimeIcons, z. B. `<i class="pi pi-check"></i>`.

## CSS

- CSS-Datei wird über `$htmlhead` eingebunden.
- `$htmlhead` wird vor `lbheader()` gesetzt.
- Cachebuster wurde erhöht.
- CSS-Klammern sind ausgeglichen.
- Keine Artefakte wie `ui-btnbefore`, `ui-btnafter`, `labelbefore`, `labelafter`.
- Alle v4-Tokens haben v3-Fallbacks.
- Responsive Darstellung wurde geprüft.

## Automatischer Frontend-Check

```bash
chmod +x scripts/check_frontend.sh
./scripts/check_frontend.sh .
```

Der Check prüft Template-Struktur, CSS-Scope, typische jQuery-Mobile-Artefakte sowie Perl/PHP-Syntax, soweit die passenden Interpreter verfügbar sind.
