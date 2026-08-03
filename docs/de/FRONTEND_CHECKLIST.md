# Frontend-Checkliste

- Seiten über `index.cgi?form=main`, `index.cgi?form=settings`, `index.cgi?form=log` testen.
- Loglink über `index.cgi?showlog=plugin` oder `index.php?showlog=plugin` prüfen.
- CSS-Scope `.plugin-page` prüfen.
- `data-role="none"` bei eigenen Buttons prüfen.
- Browser-Cache hart neu laden.

```bash
chmod +x scripts/check_frontend.sh
./scripts/check_frontend.sh .
```
