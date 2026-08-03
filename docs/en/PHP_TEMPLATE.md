# PHP frontend template

This template adds a PHP variant next to the existing Perl CGI variant for LoxBerry plugins.

## Files

```text
webfrontend/htmlauth/index.php
PHP entry point using loxberry_web.php and loxberry_system.php.

templates/index_php.html
HTML template for the PHP page.

templates/lang/language_de.ini
templates/lang/language_en.ini
Language files for German and English.
```

## Load CSS

The CSS file is loaded dynamically using the plugin folder:

```php
$cssHref = '/plugins/' . rawurlencode(LBPPLUGINDIR) . '/css/plugin.css?v=100';
$htmlhead = '<link rel="stylesheet" href="' . h($cssHref) . '">' . "\n";
```

Important: `$htmlhead` must be set before `LBWeb::lbheader()` is called.

## LoxBerry v3/v4 strategy

For maximum compatibility jQuery Mobile remains enabled. Custom controls therefore use:

```html
data-role="none"
```

CSS rules remain scoped below the plugin wrapper `.plugin-page`.

## LoxBerry v4 nojqm alternative

For a pure LoxBerry v4 Design System frontend, the header can be emitted in nojqm mode:

```php
LBWeb::lbheader($title, $helpUrl, $helpTemplate, true);
```

The template should then consistently use LoxBerry DS classes such as `lb-content`, `lb-card`, `lb-btn`, `lb-input` and `lb-form-row`.
