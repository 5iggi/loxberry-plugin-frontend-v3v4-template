# PHP frontend template

This template adds a PHP variant next to the Perl CGI variant for LoxBerry plugins.

## Files

```text
webfrontend/htmlauth/index.php
templates/index_php.html
templates/lang/language_de.ini
templates/lang/language_en.ini
```

## Load CSS

```php
$cssHref = '/plugins/' . rawurlencode($folder) . '/css/plugin.css?v=100';
$htmlhead = '<link rel="stylesheet" href="' . h($cssHref) . '">' . "\n";
```

`$htmlhead` must be set before `LBWeb::lbheader()` is called.

## v3/v4 strategy

For maximum compatibility jQuery Mobile remains active. Custom controls therefore use `data-role="none"`.

For a pure LoxBerry v4 Design System frontend, the header can be emitted in nojqm mode:

```php
LBWeb::lbheader($title, $helpUrl, $helpTemplate, true);
```
