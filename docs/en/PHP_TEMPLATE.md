# PHP Frontend Template

## Purpose

The PHP variant complements the Perl CGI variant. It is suitable for plugins that prefer building the frontend in PHP while using the same template CSS.

## Files

```text
webfrontend/htmlauth/index.php
templates/index_php.html
templates/lang/language_de.ini
templates/lang/language_en.ini
```

## Load CSS

The CSS should be added to `$htmlhead` before calling `LBWeb::lbheader()`:

```php
$cssHref = '/plugins/' . rawurlencode($folder) . '/css/plugin.css?v=102';
$htmlhead = '<link rel="stylesheet" href="' . h($cssHref) . '">' . "\n";
LBWeb::lbheader($title, $helpUrl, $helpTemplate);
```

## Languages

Language files can be loaded through LoxBerry functions:

```php
$L = LBSystem::readlanguage("language.ini");
```

## Navbar

The Navbar must be defined before `LBWeb::lbheader()`:

```php
$navbar[1]['Name'] = $L['NAV.MAIN'];
$navbar[1]['URL'] = 'index.php?form=main';
$navbar[1]['active'] = ($form === 'main');
```

## v3/v4 strategy

For maximum compatibility, jQuery Mobile remains active. Custom controls therefore use `data-role="none"`.

For a pure v4 Design System frontend, a nojqm variant can be evaluated depending on the target. This template stays neutral and v3/v4 compatible.
