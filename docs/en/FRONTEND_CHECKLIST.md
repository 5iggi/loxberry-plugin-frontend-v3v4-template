# Frontend Checklist

- Test pages via `index.cgi?form=main`, `index.cgi?form=settings`, `index.cgi?form=log`.
- Check log link via `index.cgi?showlog=plugin` or `index.php?showlog=plugin`.
- Check CSS scope `.plugin-page`.
- Check `data-role="none"` on custom buttons.
- Hard reload browser cache.

```bash
chmod +x scripts/check_frontend.sh
./scripts/check_frontend.sh .
```
