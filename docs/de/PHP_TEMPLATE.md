# PHP-Frontend-Vorlage

## Zweck

Die PHP-Variante ergänzt die Perl-CGI-Variante. Sie eignet sich für Plugins, die ihre Oberfläche lieber in PHP aufbauen und trotzdem das gleiche Template-CSS nutzen möchten.

## Dateien

```text
webfrontend/htmlauth/index.php
templates/index_php.html
templates/lang/language_de.ini
templates/lang/language_en.ini
```

## CSS laden

Das CSS sollte vor `LBWeb::lbheader()` über `$htmlhead` eingebunden werden:

```php
$cssHref = '/plugins/' . rawurlencode($folder) . '/css/plugin.css?v=102';
$htmlhead = '<link rel="stylesheet" href="' . h($cssHref) . '">' . "\n";
LBWeb::lbheader($title, $helpUrl, $helpTemplate);
```

## Sprachen

Sprachdateien können über die LoxBerry-Funktionen geladen werden:

```php
$L = LBSystem::readlanguage("language.ini");
```

## Navbar

Die Navbar muss vor `LBWeb::lbheader()` definiert werden:

```php
$navbar[1]['Name'] = $L['NAV.MAIN'];
$navbar[1]['URL'] = 'index.php?form=main';
$navbar[1]['active'] = ($form === 'main');
```

## v3/v4-Strategie

Für maximale Kompatibilität bleibt jQuery Mobile aktiv. Eigene Controls verwenden deshalb `data-role="none"`.

Für ein reines v4-Design-System-Frontend kann je nach Ziel eine nojqm-Variante geprüft werden. Diese Vorlage bleibt neutral und v3/v4-kompatibel.
