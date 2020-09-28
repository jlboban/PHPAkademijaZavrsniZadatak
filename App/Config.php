<?php

ini_set('display_errors', 1);

define("DS", DIRECTORY_SEPARATOR);
define("BP", dirname(__DIR__) . DS);
define("APP_ROOT", dirname(__FILE__, 2) . DS . "App" . DS);
define("PUB", dirname(__FILE__, 2) . DS . "pub" . DS);
define("URL_ROOT", "http://events.com/~polaznik3/");

define("VIEW_PATH", APP_ROOT . "View" . DS);
define("TEMPLATE_PATH", APP_ROOT . "View" . DS . "Templates" . DS);

define("SITE_NAME", "EventZone");