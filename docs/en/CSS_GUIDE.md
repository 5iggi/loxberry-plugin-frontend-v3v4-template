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

## 2. Use custom tokens with LoxBerry fallbacks

```css
.plugin-page {
  --plugin-primary: var(--lb-btn-primary-bg, var(--lb-primary, #6dac20));
}
```

## 3. Prevent jQuery Mobile enhancement

Custom controls should use `data-role="none"`:

```html
<button class="lb-btn plugin-button" data-role="none"><i class="pi pi-check"></i><span>Save</span></button>
```

## 4. Load CSS via htmlhead

Perl:

```perl
our $htmlhead = qq{<link rel="stylesheet" href="/plugins/$folder/css/plugin.css?v=100">\n};
```

PHP:

```php
$cssHref = '/plugins/' . rawurlencode($folder) . '/css/plugin.css?v=100';
$htmlhead = '<link rel="stylesheet" href="' . h($cssHref) . '">' . "\n";
```
