# LoxBerry Plugin Frontend v3/v4 Template

## Goal

This template helps to build a neutral LoxBerry plugin frontend that looks consistent on LoxBerry v3 and LoxBerry v4.

The template is not a pure v4-only approach. It intentionally keeps compatibility with older LoxBerry web frontends and scopes custom styling below a unique CSS wrapper.

## Included

- Perl CGI example with `form=main`, `form=settings`, `form=log`
- PHP example with the same pages
- horizontal LoxBerry menu through the integrated Navbar
- service bar with restart, stop, status and PID
- PrimeIcons in custom buttons
- CSS with LoxBerry v4 variables and v3 fallbacks
- scoped styling for LoxBerry-generated log lists
- demo log creation through the LoxBerry Bash Logging SDK
- Python daemon logging example with `WatchedFileHandler`

## Basic principle

The template separates two areas:

1. **The plugin's own UI**  
   This uses `.plugin-page`, custom buttons, PrimeIcons, `data-role="none"` and fallback CSS.

2. **LoxBerry-generated areas**  
   This includes Navbar and log list. These areas are not replaced. They are only styled carefully inside the plugin scope.

## Documentation

- [CSS Guide](CSS_GUIDE.md)
- [Frontend Checklist](FRONTEND_CHECKLIST.md)
- [Logging Notes](LOGGING_NOTES.md)
- [Logging Guidelines](LOGGING_GUIDELINES.md)
- [PHP Frontend Template](PHP_TEMPLATE.md)
- [Sources](../SOURCES.md)
