# Frontend-Checkliste

## Grundtest

- `index.cgi?form=main` testen.
- `index.cgi?form=settings` testen.
- `index.cgi?form=log` testen.
- `index.php?form=main` testen.
- `index.php?form=settings` testen.
- `index.php?form=log` testen.

## Navigation

- Prüfen, ob der aktive Navbar-Eintrag korrekt markiert bleibt.
- Nach dem Speichern auf der Einstellungsseite muss weiterhin `form=settings` aktiv sein.
- Die Navbar muss vor `lbheader()` definiert werden.

## CSS

- `.plugin-page` als Scope prüfen.
- Keine ungewollten globalen Selektoren wie `button`, `input`, `.ui-btn` verwenden.
- Eigene Buttons und Inputs mit `data-role="none"` prüfen.
- Browser-Cache hart neu laden.

## LoxBerry v3/v4

- Unter v3 prüfen, ob jQuery-Mobile-Controls nicht gestört werden.
- Unter v4 prüfen, ob v4-ähnliche Tokens und Fallbacks korrekt wirken.
- Speichern-Button grün prüfen.
- Stop-Button rot prüfen.
- Neustart-Button neutral prüfen.

## Logliste

- Demo-Log erzeugen.
- Logseite neu laden.
- Prüfen, ob die Logliste durch LoxBerry ausgegeben wird.
- Prüfen, ob der Logviewer-Button korrekt öffnet.
- Prüfen, ob die LoxBerry-generierten Collapsible-Bereiche weiterhin auf- und zuklappen.

## Automatischer Check

```bash
chmod +x scripts/check_frontend.sh
./scripts/check_frontend.sh .
```
