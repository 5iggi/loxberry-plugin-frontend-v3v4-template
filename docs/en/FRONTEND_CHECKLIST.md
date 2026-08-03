# Frontend Checklist

## Template

- All content is inside a unique wrapper, e.g. `.plugin-page`.
- Custom buttons and button links use `data-role="none"`.
- Reusable styles are moved to CSS.
- Links open new tabs or windows only when intentional.
- Check log link via `index.cgi?showlog=...` or `index.php?showlog=...`.
- Demo buttons use PrimeIcons, e.g. `<i class="pi pi-check"></i>`.

## CSS

- CSS file is included via `$htmlhead`.
- `$htmlhead` is set before `lbheader()`.
- Cache buster was increased.
- CSS braces are balanced.
- No artifacts like `ui-btnbefore`, `ui-btnafter`, `labelbefore`, `labelafter`.
- All v4 tokens have v3 fallbacks.
- Responsive layout was tested.

## Automatic frontend check

```bash
chmod +x scripts/check_frontend.sh
./scripts/check_frontend.sh .
```

The check validates template structure, CSS scope, common jQuery Mobile artifacts and Perl/PHP syntax where the required interpreters are available.
