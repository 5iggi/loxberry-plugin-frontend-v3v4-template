# LoxBerry Plugin Frontend v3/v4 Template

## Ziel

Diese Vorlage hilft beim Aufbau eines neutralen LoxBerry-Plugin-Frontends, das unter LoxBerry v3 und LoxBerry v4 möglichst einheitlich aussieht.

Das Template verfolgt keinen exklusiven v4-Ansatz. Stattdessen bleibt es bewusst kompatibel zu älteren LoxBerry-WebUIs und kapselt eigene Styles unter einem eindeutigen CSS-Scope.

## Enthalten

- Perl-CGI-Beispiel mit `form=main`, `form=settings`, `form=log`
- PHP-Beispiel mit denselben Beispielseiten
- horizontales LoxBerry-Menü über die integrierte Navbar
- Service-Leiste mit Neustart, Stop, Status und PID
- PrimeIcons in eigenen Buttons
- CSS mit LoxBerry-v4-Variablen und v3-Fallbacks
- scoped Styling für LoxBerry-generierte Loglisten
- Demo-Log-Erzeugung über das LoxBerry Bash Logging SDK
- Beispiel für Python-Daemon-Logging mit `WatchedFileHandler`

## Grundprinzip

Das Template trennt bewusst zwischen:

1. **eigener Plugin-Oberfläche**  
   Diese nutzt `.plugin-page`, eigene Buttons, PrimeIcons, `data-role="none"` und Fallback-CSS.

2. **LoxBerry-generierten Bereichen**  
   Dazu gehören insbesondere Navbar und Logliste. Diese Bereiche werden nicht ersetzt, sondern nur innerhalb des Plugin-Scopes vorsichtig optisch angepasst.

## Doku

- [CSS-Leitfaden](CSS_GUIDE.md)
- [Frontend-Checkliste](FRONTEND_CHECKLIST.md)
- [Logging-Hinweise](LOGGING_NOTES.md)
- [Logging-Guidelines](LOGGING_GUIDELINES.md)
- [PHP-Frontend-Vorlage](PHP_TEMPLATE.md)
- [Quellen](../SOURCES.md)
