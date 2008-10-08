<?php

/*
 * Path Configuration
 */
define('APP_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/../');
define('PIE_ROOT', APP_ROOT . '../../');
define('HTTP_ROOT', 'http://piephp/');
define('HTTPS_ROOT', 'https://piephp/');

/*
 * Database Configuration
 */
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'am12');
define('ENABLE_REBUILD', true);

/*
 * Memcache Configuration
 */
define('MEMCACHE_HOST', 'localhost');
define('MEMCACHE_PORT', '11211');
define('MEMCACHE_PREFIX', 'PiePHP_');

?>