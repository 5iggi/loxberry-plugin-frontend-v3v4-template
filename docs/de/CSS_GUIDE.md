# CSS-Leitfaden

## Ziel

Das CSS soll auf LoxBerry v3 und v4 funktionieren, ohne globale LoxBerry- oder jQuery-Mobile-Styles unnötig zu überschreiben.

## Scope

Alle eigenen Styles sollten unter einem eindeutigen Wrapper liegen:

```css
.plugin-page { ... }
.plugin-page .plugin-card { ... }
.plugin-page .plugin-button { ... }
```

Keine globalen Regeln wie diese verwenden:

```css
button { ... }
input { ... }
.ui-btn { ... }
```

Globale Regeln können LoxBerry-Core-Seiten, jQuery Mobile oder andere Plugins beeinflussen.

## v4-Variablen mit v3-Fallbacks

Für v4-nahe Optik können CSS-Variablen genutzt werden. Für v3 sollten immer Fallbacks angegeben werden:

```css
.plugin-page {
  --plugin-primary: var(--lb-btn-primary-bg, var(--lb-primary, #6dac20));
  --plugin-danger: var(--lb-danger, #d9534f);
  --plugin-border: var(--lb-border-color, #d8dce0);
}
```

## Buttons

Eigene Buttons sollten zusätzlich zu LoxBerry-Klassen eigene Plugin-Klassen tragen:

```html
<button class="lb-btn lb-btn-primary plugin-button plugin-button-primary" data-role="none">
  <i class="pi pi-check"></i>
  <span>Speichern</span>
</button>
```

Empfohlene Bedeutung:

- Standardbutton: neutral
- Speichern/primäre Aktion: `plugin-button-primary`
- Stop/Löschen/kritische Aktion: `plugin-button-danger`

## jQuery-Mobile-Abschirmung

Bei eigenen Controls sollte `data-role="none"` gesetzt werden, wenn jQuery Mobile nicht in das Markup eingreifen soll:

```html
<input class="lb-input" data-role="none" type="text">
<a class="lb-btn plugin-button" data-role="none" href="#">Aktion</a>
```

## LoxBerry-generierte Logliste

Die Logliste wird von LoxBerry erzeugt. Das Template ersetzt die Logliste nicht, sondern stylt sie nur im Scope:

```css
.plugin-page .plugin-loglist .ui-collapsible-heading-toggle { ... }
.plugin-page .plugin-loglist a[href*="logfile.cgi"] { ... }
```

Dadurch bleiben Logviewer-URLs, LoxBerry-JavaScript und Log-Level-Auswahl erhalten.

## PrimeIcons

PrimeIcons können in eigenen Buttons direkt eingebaut werden:

```html
<i class="pi pi-refresh"></i>
```

Bei LoxBerry-generierten Logviewer-Buttons kann ein kleines JavaScript das Icon ergänzen, ohne die vom System erzeugte URL zu verändern.
