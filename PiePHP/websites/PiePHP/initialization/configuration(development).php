<?

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
if (!defined('DB_AUTO_PATCH')) define('DB_AUTO_PATCH', true);
if (!defined('DB_TRACKING')) define('DB_TRACKING', true);

/*
 * Memcache
 */
if (!defined('CACHE_ENABLED')) define('CACHE_ENABLED', true);
if (!defined('CACHE_HOST')) define('CACHE_HOST', 'localhost');
if (!defined('CACHE_PORT')) define('CACHE_PORT', '11211');
if (!defined('CACHE_PREFIX')) define('CACHE_PREFIX', 'PiePHP_');
if (!defined('CACHE_TRACKING')) define('CACHE_TRACKING', true);

?>