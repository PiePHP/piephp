<?php

define('PIE_ROOT', 'C:/Frameworks/PiePHP/');
define('APP_ROOT', 'C:/Frameworks/PiePHP/sites/piephp.com/');

$CLASS_DIRS = array(
	'*' => PIE_ROOT . 'libraries/',
	'Controller' => APP_ROOT . 'controllers/',
	'Model' => APP_ROOT . 'models/',
	'Scaffold' => APP_ROOT . 'scaffolds/'
);

$DATABASES = array(
	'default' => array(
		'type' => 'mysql',
		'host' => 'localhost',
		'username' => 'piephp',
		'password' => '',
		'database' => 'piephp'
	)
);

$CACHES = array(
	'default' => array(
		'type' => 'memcache',
		'host' => 'localhost',
		'port' => '11211',
		'prefix' => 'piephp_',
		'expire' => '60'
	),
	'pages' => array(
		'type' => 'memcache',
		'host' => 'localhost',
		'port' => '11211',
		'prefix' => 'piephp_pages_',
		'expire' => '60',
		'pattern' => '/^\/(?!(user|refresher).*).*/i'
	)
);

$VIEW_PARAMS = array(
	'is_ajax' => strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest',
	'is_mobile' => strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false,
	'is_localhost' => $_SERVER['REMOTE_ADDR'] == '127.0.0.1'
);

@include 'config_local.php';


function __autoload($class_name) {
	global $CLASS_DIRS;
	$suffix = preg_replace('/.*([A-Z])/', '$1', $class_name);
	if ($suffix != $class_name && ($directory = $CLASS_DIRS[$suffix])) {
		if (@include($directory . $class_name . '.php')) {
			return;
		}
	}
	$directory = $CLASS_DIRS['*'];
	@include $directory . $class_name . '.php';
}


function upper_camel($underscored) {
	$spaced = preg_replace('/[^A-Za-z0-9]+/', ' ', $underscored);
	$cased = ucwords($spaced);
	$camel = str_replace(' ', '', $cased);
	return $camel ? $camel : '';
}


function lower_camel($underscored) {
	$method_name = upper_camel($underscored);
	$method_name[0] = strtolower($method_name[0]);
	return $method_name;
}


function separate($camel, $separator = '_') {
	$separated = preg_replace('/([a-z])([A-Z])/', '$1' . $separator . '$2', $camel);
	return strtolower($separated);
}


$page_path = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : $_SERVER['PATH_INFO'];

if (false && isset($CACHES['pages'])) {
	if (preg_match($CACHES['pages']['pattern'], $page_path)) {
		$page_model = new Model();
		$page_model->cacheConfigKey = 'pages';
		$page_model->cacheConnect();
		$page_cache_key = $page_path . join(',', array_values($VIEW_PARAMS));
		$contents = $page_model->cache->get($page_cache_key);
		if ($contents) {
			die($contents);
		}
		else {
			ob_start();
		}
		
	}
}

$parameters = explode('/', substr($page_path, 1));

$controller_name = upper_camel($parameters[0]) . 'Controller';

// If the URL is /hello and there's no Hello controller, then hello can be
// treated as a Home controller method.
if ($controller_name == 'Controller' || !class_exists($controller_name, true)) {
	$controller_name = 'HomeController';
}
else {
	array_shift($parameters);
}
$controller = new $controller_name();

$method_name = lower_camel($parameters[0]);

// If the URL is /controller/hello and there's no hello method on the
// controller, hello can be treated as a parameter of the default method.
if (!$method_name || !method_exists($controller, $method_name)) {
	$method_name = 'index';
}
else {
	array_shift($parameters);
}

call_user_func_array(array(&$controller, $method_name), $parameters);

if (isset($page_model)) {
	$contents = ob_get_contents();
	$page_model->cache->set($page_cache_key, $contents, 60);
}

ob_flush();
