<?php
/**
 * This is the dispatcher file which routes every request.
 * If it can find a response for the request in the page cache, it outputs that response.
 * Otherwise, it determines which Controller and Action should be used to process the request.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2007-2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

// Strict error handling promotes less error-prone code.
error_reporting(E_ALL);
set_error_handler('error_handler', E_ALL);

// To avoid revealing production passwords in development environments, we can use config_local.php
// to override database configs.
$DATABASES = array(
	'default' => 'mysql:host=localhost username=piephp password= database=piephp'
);

// Caches can be used for full-page HTML (in the dispatcher) or SQL queries (in a Model).
$CACHES = array(
	'default' => 'memcache:host=localhost port=11211 prefix=piephp_ expire=600',
	'pages' => 'memcache:host=localhost port=11211 prefix=piephp_pages_ expire=600'
);

// In development environments, we should use config_local.php to override this value.
$ENVIRONMENT = 'production';

// The version number is used when generating minified JavaScript and CSS for a deployment.
// TODO: Make this actually happen.
$VERSION = '0.0.1';

// APP_ROOT is the directory which contains this application's files.
$APP_ROOT = str_replace('\\', '/', dirname(__FILE__)) . '/';

// PIE_ROOT is the directory which contains PiePHP libraries and sites that use PiePHP.
$PIE_ROOT = dirname(dirname($APP_ROOT)) . '/';

// If a REDIRECT_URL exists, then mod_rewrite is allowing us to dispatching from '/'.
// Otherwise, we need to make URLs point to index.php by setting it as the DISPATCHER_PATH.
$DISPATCHER_PATH = isset($_SERVER['REDIRECT_URL']) ? '/' : '/index.php/';

// Any of the above settings can be overridden in a development/test/staging environment by
// rewriting them in config_local.php.
include 'config_local.php';

// Class autoloading needs to know where to look for classes with certain suffixes.
$CLASS_DIRS = array(
	'*' => $PIE_ROOT . 'libraries/',
	'Controller' => $APP_ROOT . 'controllers/',
	'Model' => $APP_ROOT . 'models/',
	'Scaffold' => $APP_ROOT . 'scaffolds/'
);

// If mod_rewrite used the path as a query string, we need to separate path data from query data.
if (!isset($_SERVER['PATH_INFO'])) {
	list($_SERVER['PATH_INFO'], $_SERVER['QUERY_STRING']) = explode('?', $_SERVER['REQUEST_URI'] . '?');
	$pairs = explode('&', $_SERVER['QUERY_STRING']);
	foreach ($pairs as $pair) {
		list($key, $value) = explode('=', $pair . '=');
		// TODO: Deal with multiple instances of the same key.
		$_GET[$key] = $value;
		$_REQUEST[$key] = $value;
	}
}
$PAGE_URL_PATH = $_SERVER['PATH_INFO'];

if (!count($_POST)) {
	$pageModel = new Model();
	$pageModel->cacheConfigName = 'pages';
	$pageModel->cacheConnect();
	$pageCacheKey = $PAGE_URL_PATH . ' '
		. (is_ajax() ? 'a' : '')
		. (is_dialog() ? 'd' : '')
		. (is_https() ? 'h' : '')
		. (is_localhost() ? 'l' : '');
	$contents = $pageModel->cache->get($pageCacheKey);
	if ($contents) {
		send_output($contents);
	}
}

ob_start();

$HTTP_BASE = 'http://' . $_SERVER['SERVER_NAME'];
$HTTPS_BASE = 'http://' . $_SERVER['SERVER_NAME'];
if (is_https()) {
	$HTTP_ROOT = $HTTP_BASE . $DISPATCHER_PATH;
	$HTTPS_ROOT = $DISPATCHER_PATH;
	$CURRENT_URL = $HTTPS_BASE . $_SERVER['REQUEST_URI'];
}
else {
	$HTTP_ROOT = $DISPATCHER_PATH;
	$HTTPS_ROOT = $HTTPS_BASE . $DISPATCHER_PATH;
	$CURRENT_URL = $HTTP_BASE . $_SERVER['REQUEST_URI'];
}

$parameters = explode('/', substr($PAGE_URL_PATH, 1));

$controllerName = upper_camel($parameters[0]) . 'Controller';

// If the URL was "/" or if it was "/hello/" and there's no HelloController, we'll just use the
// HomeController.  In the "/hello/" case, there's a chace that HomeController has a helloAction
// or a catchAllAction.
if ($controllerName == 'Controller' || !class_exists($controllerName, true)) {
	$controllerName = 'HomeController';
}
else {
	array_shift($parameters);
}
$controller = new $controllerName();

$actionName = (count($parameters) ? lower_camel($parameters[0]) : '') . 'Action';

// If the URL was "/something/hello/" then we want the helloAction of the SomethingController.  If
// the SomethingController doesn't have a helloAction, it might have a catchAllAction.  If not,
// then the Controller base catchAllAction can return a 404.
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
	$contents = ob_get_clean();
	$contents = preg_replace('/>[\\r\\n\\t]+</ms', '><', $contents);
	$contents = preg_replace('/\\s+/ms', ' ', $contents);
	$pageModel->cache->set($pageCacheKey, $contents, isset($PAGE_CACHE_TIME) ? $PAGE_CACHE_TIME : 60);
	send_output($contents);
}

/**
 * Check through the appropriate entries in $CLASS_DIRS to find the class that we're trying to use.
 * @param  $className: the name of the class we're trying to use.
 */
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

/**
 * Convert a name separated by underscores (or other non-alphanumerics) to UpperCamelCase.
 * @param  $underscored: the name that is separated by underscores.
 * @return the name in UpperCamelCase.
 */
function upper_camel($underscored) {
	$spaced = preg_replace('/[^A-Za-z0-9]+/', ' ', $underscored);
	$cased = ucwords($spaced);
	$camel = str_replace(' ', '', $cased);
	return $camel ? $camel : '';
}

/**
 * Convert a name separated by underscores (or other non-alphanumerics) to lowerCamelCase.
 * @param  $underscored: the name that is separated by underscores.
 * @return the name in lowerCamelCase.
 */
function lower_camel($underscored) {
	$actionName = upper_camel($underscored);
	if ($actionName) {
		$actionName[0] = strtolower($actionName[0]);
	}
	return $actionName;
}

/**
 * Separate a camel case name into words using separators such as underscores.
 * @param  $camel: the name in upper or lower camel case.
 * @param  $separator: the separator that we want to insert between words.
 * @return the separated words as a string.
 */
function separate($camel, $separator = '_') {
	$camel = preg_replace('/[^a-zA-Z0-9]+/', $separator, $camel);
	$separated = preg_replace('/([a-z])([A-Z])/', '$1' . $separator . '$2', $camel);
	return strtolower($separated);
}

/**
 * Handle PHP errors using the ErrorsController.
 * @param  $level: the PHP error level.
 * @param  $message: the message describing the error.
 * @param  $file: the file in which the error occurred.
 * @param  $lineNumber: the line number on which the error occurred.
 * @param  $context: the values of local variables at the time when the error happened.
 * @return true to prevent default error handling.
 */
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

/**
 * When HTML has come from the cache, it won't yet contain stuff that's specific to a signed-in
 * user (such as "Welcome, Sam" and "Sign out").  So if the user is signed in, we should decorate
 * the cached HTML with user stuff. Then we'll output it through the output buffer's gzip handler.
 * @param  $output: the output to decorate and send.
 */
function send_output($output) {
	global $DISPATCHER_PATH;
	$session = new Session();
	if ($session->isSignedIn) {
		$pieces = preg_split('/<div id="user">.*?<\/div>/', $output, 2);
		if (count($pieces) > 1) {
			$output = $pieces[0] .
				'<div id="user">' .
					'<span>' . htmlentities($session->username) . '</span>' .
					'<div id="userNav">' .
					'<a href="' . $DISPATCHER_PATH . 'admin/">Admin</a>' .
					'<a href="' . $DISPATCHER_PATH . 'sign_out/" class="noAjax">Sign out</a>' .
					'</div>' .
				'</div>' .
				$pieces[1];
		}
	}
	ob_start('ob_gzhandler');
	echo $output;
	exit;
}

/**
 * Whether a page was requested via AJAX
 * @return true if the page was requested via AJAX.
 */
function is_ajax() {
	return (isset($_REQUEST['isAjax']) && $_REQUEST['isAjax']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
}

/**
 * Whether a page is being viewed in a veil dialog.
 * @return true if the page was requested via a veil dialog.
 */
function is_dialog() {
	return isset($_REQUEST['isDialog']);
}

/**
 * Whether a page was reached via a submitter iframe.
 * @return true if the page was requested via AJAX.
 */
function is_frame() {
	return isset($_REQUEST['isFrame']);
}

/**
 * Whether the protocol is HTTPS.
 * @return true if the protocol is HTTPS.
 */
function is_https() {
	return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off';
}

/**
 * Whether the page is being viewed on the machine it is being served from.
 * @return true if it's being viewed on localhost.
 */
function is_localhost() {
	return $_SERVER['REMOTE_ADDR'] == '127.0.0.1';
}

/**
 * Whether the page is being viewed on a mobile device.
 * @return true if it is being viewed on a mobile device.
 */
function is_mobile() {
	strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false;
}

/**
 * Print a value to the page with formatting preserved.
 * @param  $var: the value to print.
 */
function p($var) {
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

/**
 * Print a value to the page with formatting preserved, then exit.
 * @param  $var: the value to print.
 */
function x($var) {
	p($var);
	exit;
}

