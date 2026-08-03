# PHP-Frontend-Vorlage

Diese Vorlage ergänzt die bestehende Perl-CGI-Variante um eine PHP-Variante für LoxBerry-Plugins.

## Dateien

```text
webfrontend/htmlauth/index.php
PHP-Einstiegspunkt mit loxberry_web.php und loxberry_system.php.

templates/index_php.html
HTML-Template für die PHP-Seite.

templates/lang/language_de.ini
templates/lang/language_en.ini
Sprachdateien für Deutsch und Englisch.
```

## CSS laden

Die CSS-Datei wird dynamisch über den Plugin-Ordner eingebunden:

```php
$cssHref = '/plugins/' . rawurlencode(LBPPLUGINDIR) . '/css/plugin.css?v=100';
$htmlhead = '<link rel="stylesheet" href="' . h($cssHref) . '">' . "\n";
```

Wichtig: `$htmlhead` muss vor `LBWeb::lbheader()` gesetzt werden.

## LoxBerry v3/v4 Strategie

Für maximale Kompatibilität bleibt jQuery Mobile aktiv. Eigene Controls verwenden deshalb:

```html
data-role="none"
```

Die CSS-Regeln bleiben unter dem Plugin-Wrapper `.plugin-page` gekapselt.

## LoxBerry v4 nojqm Alternative

Für ein reines LoxBerry-v4-Design-System-Frontend kann der Header mit nojqm ausgegeben werden:

```php
LBWeb::lbheader($title, $helpUrl, $helpTemplate, true);
```

Dann sollte das Template konsequent LoxBerry-DS-Klassen wie `lb-content`, `lb-card`, `lb-btn`, `lb-input` und `lb-form-row` verwenden.
