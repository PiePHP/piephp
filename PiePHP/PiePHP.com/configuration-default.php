<?php

/*
 * Path Configuration
 */
define('DOCS', $_SERVER['DOCUMENT_ROOT']);
define('PIE', preg_replace('/\/[^\/]+$/', '', $_SERVER['DOCUMENT_ROOT']));
define('ROOT', '/');
define('HTTP', 'http://localhost/');
define('HTTPS', 'https://localhost/');

/*
 * Database Configuration
 */
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'password');
define('ENABLE_REBUILD', true);

/*
 * Memcache Configuration
 */
define('MEMCACHE_HOST', 'localhost');
define('MEMCACHE_PORT', '11211');
define('MEMCACHE_PREFIX', 'PiePHP_');

?>