# CSS-Leitfaden

## 1. Plugin-Scope verwenden

Jedes Plugin sollte einen eigenen Wrapper haben:

```html
<div class="plugin-page">
  ...
</div>
```

Alle CSS-Regeln sollten auf diesen Scope begrenzt werden:

```css
.plugin-page .plugin-button { ... }
```

## 2. Eigene Token mit LoxBerry-Fallbacks

```css
.plugin-page {
  --plugin-primary: var(--lb-btn-primary-bg, var(--lb-primary, #6dac20));
}
```

## 3. jQuery Mobile umgehen

Eigene Controls sollten `data-role="none"` nutzen:

```html
<button class="lb-btn plugin-button" data-role="none"><i class="pi pi-check"></i><span>Speichern</span></button>
```

## 4. CSS über htmlhead laden

Perl:

```perl
our $htmlhead = qq{<link rel="stylesheet" href="/plugins/$folder/css/plugin.css?v=100">\n};
```

PHP:

```php
$cssHref = '/plugins/' . rawurlencode($folder) . '/css/plugin.css?v=100';
$htmlhead = '<link rel="stylesheet" href="' . h($cssHref) . '">' . "\n";
```
