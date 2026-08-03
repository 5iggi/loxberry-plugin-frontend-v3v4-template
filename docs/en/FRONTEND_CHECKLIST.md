# Frontend Checklist

## Template

- [ ] All content is inside a unique wrapper, e.g. `.plugin-page`.
- [ ] Custom buttons and button links use `data-role="none"`.
- [ ] Reusable styles are moved to CSS.
- [ ] Links open new tabs or windows only when intentional.

## CSS

- [ ] CSS file is included via `$htmlhead`.
- [ ] `$htmlhead` is set before `lbheader()`.
- [ ] Cache buster was increased.
- [ ] CSS braces are balanced.
- [ ] No artifacts like `ui-btnbefore`, `ui-btnafter`, `labelbefore`, `labelafter`.
- [ ] All v4 tokens have v3 fallbacks.
- [ ] Responsive layout was tested.

## CGI / Perl

- [ ] Do not use generic function names like `trim()`.
- [ ] Prefer `plugin_trim()`, `myplugin_trim()` or similar.
- [ ] Check `perl -c index.cgi` on a suitable system.
- [ ] AJAX responses return JSON with UTF-8.

## Release test

- [ ] Check ZIP with `unzip -t`.
- [ ] Check shell scripts with `bash -n`.
- [ ] Test installation on LoxBerry v3.
- [ ] Test installation on LoxBerry v4.
- [ ] Hard reload browser cache.
