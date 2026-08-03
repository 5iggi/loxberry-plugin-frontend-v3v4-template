# CSS Guide

## 1. Use a plugin scope

Every plugin should have a unique wrapper:

```html
<div class="plugin-page">
  ...
</div>
```

All CSS rules should be scoped below that wrapper:

```css
.plugin-page .plugin-button { ... }
```

This prevents accidental changes to LoxBerry system pages or other plugins.

## 2. Use custom tokens with LoxBerry fallbacks

Recommended pattern:

```css
.plugin-page {
  --plugin-primary: var(--lb-btn-primary-bg, var(--lb-primary, #6dac20));
  --plugin-primary-hover: var(--lb-primary-hover, #5a9a1a);
  --plugin-danger: var(--lb-danger, #dc2626);
  --plugin-danger-hover: var(--lb-danger-hover, var(--lb-color-danger-hover, #b91c1c));
}
```

This lets LoxBerry v4 use existing tokens and gives LoxBerry v3 stable fallback values.

## 3. Prevent jQuery Mobile enhancement for custom buttons

Custom buttons should look like this:

```html
<button class="lb-btn plugin-button" data-role="none">Save</button>
```

`data-role="none"` prevents jQuery Mobile from restyling these buttons.

## 4. Load CSS via htmlhead

Perl:

```perl
our $htmlhead = qq{<link rel="stylesheet" href="/plugins/PLUGINNAME/css/plugin.css?v=100">\n};
LoxBerry::Web::lbheader($plugintitle, $helplink, $helptemplate);
```

PHP:

```php
$cssHref = '/plugins/' . rawurlencode(LBPPLUGINDIR) . '/css/plugin.css?v=100';
$htmlhead = '<link rel="stylesheet" href="' . h($cssHref) . '">' . "\n";
LBWeb::lbheader($title, $helpUrl, $helpTemplate);
```

Important: `$htmlhead` must be set before `lbheader()` is called.

## 5. Maintain the cache buster

Increase the version on every visible CSS change:

```text
v=100
v=101
v=102
```

## 6. Typical mistakes

- Global `.lb-btn` rules without plugin scope.
- Wrong pseudo selectors like `ui-btnbefore` instead of `.ui-btn::before`.
- Copying CSS from HTML views where characters may be escaped.
- Custom buttons without `data-role="none"`.
