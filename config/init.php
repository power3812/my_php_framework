<?php

error_reporting(E_ALL | E_STRICT);
date_default_timezone_set('Asia/Tokyo');

define('BASE_URI_PATH', '');
define('PROJECT_ROOT', '/Users/nakashimayuta/Documents/GitHub/my_php_framework' . BASE_URI_PATH);
define('DOMAIN', 'localhost:8003');

define('CLASS_FILES_DIR', PROJECT_ROOT . '/classes');
define('LIB_FILES_DIR',   PROJECT_ROOT . '/lib');
define('ADMIN_FILES_DIR', PROJECT_ROOT . '/admin');
define('HTML_FILES_DIR',  PROJECT_ROOT . '/html');
define('LOG_FILES_DIR',   PROJECT_ROOT . '/logs');

define('SESSION_TIME', 60 * 60 * 60);

require_once(PROJECT_ROOT  . '/config/database.php');
require_once(PROJECT_ROOT  . '/lib/functions.php');
require_once(LIB_FILES_DIR . '/ClassLoader.php');

add_include_path(CLASS_FILES_DIR);
add_include_path(LIB_FILES_DIR);
add_include_path(ADMIN_FILES_DIR);

spl_autoload_register(['ClassLoader', 'autoload']);
