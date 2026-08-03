# Frontend Checklist

## Template

- All content is inside a unique wrapper, e.g. `.plugin-page`.
- Custom buttons and button links use `data-role="none"`.
- Reusable styles are moved to CSS.
- Links open new tabs or windows only when intentional.

## CSS

- CSS file is included via `$htmlhead`.
- `$htmlhead` is set before `lbheader()`.
- Cache buster was increased.
- CSS braces are balanced.
- No artifacts like `ui-btnbefore`, `ui-btnafter`, `labelbefore`, `labelafter`.
- All v4 tokens have v3 fallbacks.
- Responsive layout was tested.

## CGI / Perl

- Do not use generic function names like `trim()`.
- Prefer `plugin_trim()`, `myplugin_trim()` or similar.
- Check `perl -c index.cgi` on a suitable system.
- AJAX responses return JSON with UTF-8.

## PHP

- Use `loxberry_web.php` and `loxberry_system.php`.
- Build the CSS path dynamically using `LBPPLUGINDIR`.
- Use `LBSystem::readlanguage("language.ini")` for language files.
- Check PHP syntax with `php -l index.php` if `php-cli` is available.

## Automatic frontend check

The repository contains a helper script:

```bash
scripts/check_frontend.sh
```

The script checks for common frontend structure, template and CSS issues.

### Run

From the repository root:

```bash
./scripts/check_frontend.sh .
```

Or with an explicit path:

```bash
./scripts/check_frontend.sh /path/to/plugin
```

If the script is not executable:

```bash
chmod +x scripts/check_frontend.sh
./scripts/check_frontend.sh .
```

### What is checked?

The script checks, among other things:

- whether the template contains a unique plugin wrapper, e.g. `.plugin-page`
- whether custom buttons use `data-role="none"`
- whether custom button classes are present
- whether the CSS file has balanced `{}` braces
- whether the CSS file uses the plugin scope
- whether CSS tokens such as `--plugin-primary` are present
- whether common copy/paste mistakes such as `ui-btnbefore`, `ui-btnafter`, `labelbefore` or `labelafter` are present
- whether `index.cgi` can be checked with `perl -c`
- whether `index.php` can be checked with `php -l`, if `php-cli` is installed

### Note

The check does not replace testing on a real LoxBerry v3 or v4 system. It helps to detect common structure, CSS and template issues before a release.

## Release test

- Check ZIP with `unzip -t`.
- Check shell scripts with `bash -n`.
- Test installation on LoxBerry v3.
- Test installation on LoxBerry v4.
- Hard reload browser cache.
