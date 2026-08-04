# Sources / Quellen

The following LoxBerry sources were used to update the documentation.

## LoxBerry Web UI and navbar

- LoxBerry Wiki: **Navigation Bar (Menu)**  
  https://wiki.loxberry.de/entwickler/web_ui_development_in_loxberry/navigation_bar_menu

Key points used:

- LoxBerry provides a Navbar for plugins.
- Perl and PHP frontends can define the Navbar before calling `lbheader()` / `pagestart()`.
- In Perl, a global `%navbar` can be used; setting `active = 1` explicitly marks the active item.
- From LoxBerry 3.0, `@navbar` and HTML/JavaScript variants exist, but `%navbar` remains a simple compatible choice.

## LoxBerry log list in frontend

- LoxBerry Wiki: **LBWeb::loglist_html**  
  https://wiki.loxberry.de/entwickler/php_develop_plugins_with_php/php_loxberry_sdk_documentation/php_module_loxberry_webphp/lbwebloglist_html

- LoxBerry Wiki: **LoxBerry::Web::loglist_html**  
  https://wiki.loxberry.de/entwickler/perl_develop_plugins_with_perl/perl_loxberry_sdk_dokumentation/perlmodul_loxberryweb/loxberrywebloglist_html

Key points used:

- `loglist_html()` returns ready-made HTML for a plugin log list.
- The function is intended for a Logfiles tab in a plugin frontend.
- The function works with logs created through the LoxBerry Logging SDK, because the list is based on LoxBerry's log handling and log database.
- Manually copied log files are not a reliable way to populate this list.

## LoxBerry Bash logging

- LoxBerry Wiki: **LoxBerry Logging in Bash**  
  https://wiki.loxberry.de/entwickler/bash_supporting_scripts_for_your_plugin_development/bash_loxberry_sdk_documentation/loxberry_logging_in_bash

Key points used:

- Bash scripts can use `$LBHOMEDIR/libs/bashlib/loxberry_log.sh`.
- Common variables are `PACKAGE`, `NAME`, `FILENAME`, and optional `APPEND`.
- `PACKAGE` must match the plugin folder so that the log can be associated with the plugin.
- `LOGSTART`, `LOGINF`, `LOGOK`, `LOGWARN`, `LOGERR`, and `LOGEND` are used to write session logs.

## LoxBerry Log Manager and cleanup behavior

- LoxBerry Wiki: **Widget Log Manager**  
  https://wiki.loxberry.de/konfiguration/widget_help/widget_log_manager

- LoxBerry Wiki: **Message The logfile database sends an error and cannot automatically be recovered**  
  https://wiki.loxberry.de/haufig_gestellte_fragen_faq/message_the_logfile_database_sends_an_error_and_cannot_automatically_be_recovered

Key points used:

- LoxBerry keeps track of log files and automatically deletes or truncates old or large logs.
- Cleanup can also happen when the log folder tmpfs/RAM disk is low on free space.
- The Log Manager "Logfiles" tab shows logging sessions, not just raw files.
- Sessions may remain visible even if the underlying file was truncated or deleted.
- The log database is stored on tmpfs and may be recreated if corrupted.
- If the database cannot be written, log creation may fail and logs may only appear under "More Logfiles" or not be listed through `loglist_html()`.

## Linux file handle behavior for deleted logs

- LoxBerry Wiki and forum discussions mention the practical problem that a process may keep writing to a deleted file handle. This is a Linux behavior and is relevant for long-running daemons that write to files in LoxBerry log directories.

Practical implication:

- Services should reopen recreated log files or use log handlers that can detect external rotation or deletion.
- For Python daemons, `logging.handlers.WatchedFileHandler` is a suitable example.
