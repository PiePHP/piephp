<?php

ob_start('ob_gzhandler');
error_reporting(E_ALL);
set_error_handler($ERROR_HANDLER = 'error_handler', E_ALL);

$DATABASES = array(
	'default' => 'mysql:host=localhost username=piephp password= database=piephp'
);

$CACHES = array(
	'default' => 'memcache:host=localhost port=11211 prefix=piephp_ expire=600',
	'pages' => 'memcache:host=localhost port=11211 prefix=piephp_pages_ expire=600'
);

$SERVER_NAME = 'pie';
$URL_ROOT = '/';
$PIE_ROOT = "C:/Frameworks/PiePHP/";
$APP_ROOT = "C:/Frameworks/PiePHP/sites/piephp.com/";

@include 'config_local.php';

$CLASS_DIRS = array(
	'*' => $PIE_ROOT . 'libraries/',
	'Controller' => $APP_ROOT . 'controllers/',
	'Model' => $APP_ROOT . 'models/',
	'Scaffold' => $APP_ROOT . 'scaffolds/'
);

$PAGE_PATH = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['QUERY_STRING'];

if (!count($_POST)) {
	$pageModel = new Model();
	$pageModel->cacheConfigName = 'pages';
	$pageModel->cacheConnect();
	$pageCacheKey = $PAGE_PATH . ' ' . (is_ajax() ? 'a' : 0) . (is_https() ? 'h' : 0) . (is_localhost() ? 'l' : 0) . (is_mobile() ? 'm' : 0);
	$contents = $pageModel->cache->get($pageCacheKey);
	if ($contents) {
		echo $contents;
		exit;
	}
}

$HTTP_BASE = 'http://' . $SERVER_NAME;
$HTTPS_BASE = 'http://' . $SERVER_NAME;
if (is_https()) {
	$HTTP_ROOT = $HTTP_BASE . $URL_ROOT;
	$HTTPS_ROOT = $URL_ROOT;
}
else {
	$HTTP_ROOT = $URL_ROOT;
	$HTTPS_ROOT = $HTTPS_BASE . $URL_ROOT;
}

$parameters = explode('/', substr($PAGE_PATH, 1));

$controllerName = upper_camel($parameters[0]) . 'Controller';

// If the URL is /hello and there's no Hello controller, then hello can be treated as a Home controller method.
if ($controllerName == 'Controller' || !class_exists($controllerName, true)) {
	$controllerName = 'HomeController';
}
else {
	array_shift($parameters);
}
$controller = new $controllerName();

$actionName = (count($parameters) ? lower_camel($parameters[0]) : '') . 'Action';

// If the URL is /controller/hello and there's no hello method, hello is a parameter of the index method.
if ($actionName == 'Action') {
	$actionName = 'indexAction';
}
if (!method_exists($controller, $actionName)) {
	$actionName = 'catchAllAction';
}
else {
	array_shift($parameters);
}

call_user_func_array(array(&$controller, $actionName), $parameters);

if ($controller->isCacheable && isset($pageModel)) {
	$contents = ob_get_contents();
	$contents = preg_replace('/>[\\r\\n\\t]+</ms', '><', $contents);
	$contents = preg_replace('/\\s+/ms', ' ', $contents);
	$pageModel->cache->set($pageCacheKey, $contents, isset($PAGE_CACHE_TIME) ? $PAGE_CACHE_TIME : 60);
}

function __autoload($className) {
	global $CLASS_DIRS;
	$suffix = preg_replace('/.*([A-Z])/', '$1', $className);
	$autoloadFile = $className . '.php';
	if ($suffix != $className && isset($CLASS_DIRS[$suffix]) && ($directory = $CLASS_DIRS[$suffix])) {
		if (@include($directory . $autoloadFile)) {
			return;
		}
	}
	$directory = $CLASS_DIRS['*'];
	@include $directory . $autoloadFile;
}


function upper_camel($underscored) {
	$spaced = preg_replace('/[^A-Za-z0-9]+/', ' ', $underscored);
	$cased = ucwords($spaced);
	$camel = str_replace(' ', '', $cased);
	return $camel ? $camel : '';
}


function lower_camel($underscored) {
	$actionName = upper_camel($underscored);
	if ($actionName) {
		$actionName[0] = strtolower($actionName[0]);
	}
	return $actionName;
}


function separate($camel, $separator = '_') {
	$camel = preg_replace('/[^a-zA-Z0-9]+/', $separator, $camel);
	$separated = preg_replace('/([a-z])([A-Z])/', '$1' . $separator . '$2', $camel);
	return strtolower($separated);
}


function error_handler($level, $message, $file, $lineNumber, $context) {
	// Ignore certain warnings.
	if ($level == 2) {
		// We failed to find a class file, but we might have just been checking if a class exists.
		if (isset($context['autoloadFile'])) {
			return;
		}
	}
	global $ERRORS_CONTROLLER;
	if (!$ERRORS_CONTROLLER) {
		$ERRORS_CONTROLLER = new ErrorsController();
	}
	$ERRORS_CONTROLLER->handleError($level, $message, $file, $lineNumber, $context);
	return true;
}

function is_ajax() {
	return isset($_REQUEST['is_ajax']) || isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
}

function is_https() {
	return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off';
}

function is_localhost() {
	return $_SERVER['REMOTE_ADDR'] == '127.0.0.1';
}

function is_mobile() {
	strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false;
}

function p($var) {
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

function d($var) {
	p($var);
	exit;
}

