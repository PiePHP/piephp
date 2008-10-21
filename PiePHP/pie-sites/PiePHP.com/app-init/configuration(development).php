<?php

/*
 * Path Configuration
 */
if (!defined('APP_ROOT')) define('APP_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/../');
if (!defined('PIE_ROOT')) define('PIE_ROOT', APP_ROOT . '../../');
if (!defined('HTTP_ROOT')) define('HTTP_ROOT', 'http://piephp/');
if (!defined('HTTPS_ROOT')) define('HTTPS_ROOT', 'https://piephp/');

/*
 * Database Configuration
 */
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_USER')) define('DB_USER', 'piephp');
if (!defined('DB_PASS')) define('DB_PASS', 'password');
if (!defined('ENABLE_REBUILD')) define('ENABLE_REBUILD', true);

/*
 * Memcache Configuration
 */
if (!defined('MEMCACHE_HOST')) define('MEMCACHE_HOST', 'localhost');
if (!defined('MEMCACHE_PORT')) define('MEMCACHE_PORT', '11211');
if (!defined('MEMCACHE_PREFIX')) define('MEMCACHE_PREFIX', 'PiePHP_');

?>