# Logging Notes

LoxBerry logging is session based for actions. For web frontend actions, installation steps and diagnostics, prefer the LoxBerry Logging SDK.

This template uses the plugin main logfile for the demo log button:

```text
/opt/loxberry/log/plugins/<plugin>/<plugin>.log
```

The button calls the plugin frontend first:

```text
index.cgi?showlog=plugin
index.php?showlog=plugin
```

This ensures the logfile exists before opening it in the LoxBerry Log Viewer.
