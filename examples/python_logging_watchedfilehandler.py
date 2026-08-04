#!/usr/bin/env python3
"""Optional logging example for plugins with a Python daemon."""

import logging
import os
from logging.handlers import WatchedFileHandler

LOGFILE = "/opt/loxberry/log/plugins/PLUGINNAME/PLUGINNAME.log"
LOGFORMAT = "%(asctime)-15s <%(levelname)s> %(message)s"


def setup_logging(verbose=False):
    os.makedirs(os.path.dirname(LOGFILE), exist_ok=True)
    handler = WatchedFileHandler(LOGFILE, mode="a", encoding="utf-8", delay=True)
    handler.setFormatter(logging.Formatter(LOGFORMAT))

    root = logging.getLogger()
    root.setLevel(logging.DEBUG if verbose else logging.INFO)
    root.handlers.clear()
    root.addHandler(handler)


if __name__ == "__main__":
    setup_logging()
    logging.info("PLUGINNAME daemon started")
