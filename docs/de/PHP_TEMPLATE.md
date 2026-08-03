# PHP-Frontend-Vorlage

Diese Vorlage ergänzt die Perl-CGI-Variante um eine PHP-Variante für LoxBerry-Plugins.

## Dateien

```text
webfrontend/htmlauth/index.php
templates/index_php.html
templates/lang/language_de.ini
templates/lang/language_en.ini
```

## CSS laden

```php
$cssHref = '/plugins/' . rawurlencode($folder) . '/css/plugin.css?v=100';
$htmlhead = '<link rel="stylesheet" href="' . h($cssHref) . '">' . "\n";
```

`$htmlhead` muss vor `LBWeb::lbheader()` gesetzt werden.

## v3/v4 Strategie

Für maximale Kompatibilität bleibt jQuery Mobile aktiv. Eigene Controls verwenden deshalb `data-role="none"`.

Für ein reines LoxBerry-v4-Design-System-Frontend kann der Header mit nojqm ausgegeben werden:

```php
LBWeb::lbheader($title, $helpUrl, $helpTemplate, true);
```
