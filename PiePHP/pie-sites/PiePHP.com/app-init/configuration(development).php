<?php

/*
 * Environment
 */
if (!defined('ENVIRONMENT')) define('ENVIRONMENT', 'development');

/*
 * Paths
 */
if (!defined('APP_ROOT')) define('APP_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/../');
if (!defined('PIE_ROOT')) define('PIE_ROOT', APP_ROOT . '../../');
if (!defined('HTTP_ROOT')) define('HTTP_ROOT', 'http://piephp/');
if (!defined('HTTPS_ROOT')) define('HTTPS_ROOT', 'https://piephp/');
if (!defined('SVN_ROOT')) define('SVN_ROOT', APP_ROOT);

/*
 * Database
 */
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_USER')) define('DB_USER', 'piephp');
if (!defined('DB_PASS')) define('DB_PASS', 'password');

/*
 * Memcache
 */
if (!defined('MEMCACHE_ENABLED')) define('MEMCACHE_ENABLED', false);
if (!defined('MEMCACHE_HOST')) define('MEMCACHE_HOST', 'localhost');
if (!defined('MEMCACHE_PORT')) define('MEMCACHE_PORT', '11211');
if (!defined('MEMCACHE_PREFIX')) define('MEMCACHE_PREFIX', 'PiePHP_');

?>