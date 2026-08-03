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
.plugin-page .plugin-button {
  ...
}
```

So werden andere LoxBerry-Seiten und andere Plugins nicht beeinflusst.

## 2. Eigene Token mit LoxBerry-Fallbacks

Empfohlenes Muster:

```css
.plugin-page {
  --plugin-primary: var(--lb-btn-primary-bg, var(--lb-primary, #6dac20));
  --plugin-primary-hover: var(--lb-primary-hover, #5a9a1a);
  --plugin-danger: var(--lb-danger, #dc2626);
  --plugin-danger-hover: var(--lb-danger-hover, var(--lb-color-danger-hover, #b91c1c));
}
```

Damit nutzt LoxBerry v4 vorhandene Tokens und LoxBerry v3 stabile Fallback-Werte.

## 3. jQuery Mobile umgehen, wo eigene Buttons genutzt werden

Eigene Buttons sollten so aussehen:

```html
<a data-role="none" class="plugin-button plugin-button-danger" href="#">Stop</a>
<button data-role="none" class="plugin-button plugin-button-primary">Speichern</button>
```

`data-role="none"` verhindert, dass jQuery Mobile die Buttons erneut gestaltet.

## 4. CSS ueber htmlhead laden

```perl
our $htmlhead = qq{<link rel="stylesheet" href="/plugins/PLUGINNAME/css/plugin.css?v=100">\n};
LoxBerry::Web::lbheader($plugintitle, $helplink, $helptemplate);
```

Wichtig: `$htmlhead` muss vor `lbheader()` gesetzt werden.

## 5. Cachebuster pflegen

Bei jeder sichtbaren CSS-Aenderung hochzaehlen:

```text
v=100
v=101
v=102
```

## 6. Typische Fehler

- Globale `.lb-btn`-Regeln ohne Plugin-Scope.
- Falsche Pseudoselektoren wie `ui-btnbefore` statt `.ui-btn::before`.
- CSS aus HTML-Ansichten kopieren, wodurch Zeichen escaped werden.
- Buttons ohne `data-role="none"`, obwohl eigenes Button-CSS verwendet wird.
