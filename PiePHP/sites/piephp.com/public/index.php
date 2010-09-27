<?php
/**
 * This is the dispatcher file which routes every request.
 * If it can find a response for the request in the page cache, it outputs that response.
 * Otherwise, it determines which Controller and Action should be used to process the request.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

// Strict error handling promotes less error-prone code.
error_reporting(E_ALL);
set_error_handler('error_handler', E_ALL);

/**
 * The configuration settings below are the default configuration for deployments
 * of this application.  Local configurations can override the defaults using
 * their own "localConfig.php", which should be ignored by source control clients.
 */

// Database configurations are included here, but we do not make database connections
// until data is needed.  In many cases, we can try getting data from a cache first.
$DATABASES = array(
	'default' => 'mysql:host=localhost username=piephp password=password database=piephp'
);

// Caches can be used for things like query results and full-page HTML.
$CACHES = array(
	'default' => 'memcache:host=localhost port=11211 prefix=piephp_ expire=600',
	'pages' => 'file:host=localhost port=11211 prefix=piephp_pages_ expire=600'
);

// In development environments, we should use localConfig.php to override this value.
$ENVIRONMENT = 'production';

// The version number is used when generating minified JavaScript and CSS for a deployment.
// TODO: Make this actually happen.
$VERSION = '0.0.1';

// $SITE_DIR is the directory which contains this application's files.
$SITE_DIR = str_replace('\\', '/', dirname(dirname(__FILE__))) . '/';

// $PIE_DIR is the directory which contains PiePHP libraries and sites that use PiePHP.
$PIE_DIR = dirname(dirname($SITE_DIR)) . '/';

// $URL_ROOT is the part of the URL that spans from the server name (and port, if any) to the
// controller name.  This is the URL for the dispatcher, and is usually "/" or "/index.php/".
// For deployments to servers without mod_rewrite, this can be changed in localConfig.php.
$URL_ROOT = '/';

// Any of the above settings can be overridden in a development/test/staging environment by
// rewriting them in localConfig.php.
include $SITE_DIR . 'localConfig.php';

// If we're not posting data, we should check for a cached copy of the requested page.
if (!count($_POST) && isset($CACHES['pages'])) {
	include $PIE_DIR . 'classes/Model.php';
	include $PIE_DIR . 'classes/' . ($CACHES['pages'][0] == 'f' ? 'File' : 'Memcache') . 'Cache.php';
	$pageModel = new Model();
	$pageModel->cacheConfigName = 'pages';
	$pageModel->loadCache();
	$pageCacheKey = $_SERVER['REQUEST_URI'] . '&'
	. (is_ajax() ? 'a' : '')
	. (is_dialog() ? 'd' : '')
	. (is_https() ? 'h' : '')
	. (is_localhost() ? 'l' : '');
	$contents = $pageModel->cache->get($pageCacheKey);
	if ($contents) {
		ob_start('ob_gzhandler');
		send_output($contents);
	}
}

// Output buffering must be turned on in order to support post-rendering source modifications.
// TODO: Find out why PHP won't let me get the contents of a gzipped buffer.
ob_start();

// The REQUEST_URI is what was requested before mod_rewrite changed anything.
// For a URL like "http://server:port/url/to/the/page?query=string",
// the URL_ROOT will be "/url/to/the/page".
// and the QUERY_STRING will be "query=string".
list($URL_PATH, $QUERY_STRING) = explode('?', $_SERVER['REQUEST_URI'] . '?');
if (strpos($URL_PATH, $URL_ROOT) === false) {
	$URL_PATH = str_replace('//', '/', $URL_ROOT . $URL_PATH);
}

// If mod_rewrite used the path as a query string, we need to separate path data from query data.
if (isset($_SERVER['REDIRECT_QUERY_STRING'])) {
	$pairs = explode('&', $QUERY_STRING);
	foreach ($pairs as $pair) {
		list($key, $value) = explode('=', $pair . '=');
		// TODO: Deal with multiple instances of the same key.
		$_GET[$key] = $value;
		$_REQUEST[$key] = $value;
	}
}

$HTTP_BASE = 'http://' . $_SERVER['SERVER_NAME'];
$HTTPS_BASE = 'http://' . $_SERVER['SERVER_NAME'];
if (is_https()) {
	$HTTP_ROOT = $HTTP_BASE . $URL_ROOT;
	$HTTPS_ROOT = $URL_ROOT;
}
else {
	$HTTP_ROOT = $URL_ROOT;
	$HTTPS_ROOT = $HTTPS_BASE . $URL_ROOT;
}

$PARAMETERS = explode('/', substr($URL_PATH, strlen($URL_ROOT)));

$CONTROLLER_NAME = upper_camel($PARAMETERS[0]) . 'Controller';

// If the URL was "/" or if it was "/hello/" and there's no HelloController, we'll just use the
// HomeController.  In the "/hello/" case, there's a chace that HomeController has a helloAction
// or a catchAllAction.
if ($CONTROLLER_NAME == 'Controller' || !class_exists($CONTROLLER_NAME, true)) {
	$CONTROLLER_NAME = 'HomeController';
}
else {
	array_shift($PARAMETERS);
}
$CONTROLLER = new $CONTROLLER_NAME();

$ACTION_NAME = (count($PARAMETERS) ? lower_camel($PARAMETERS[0]) : '') . 'Action';

// If the URL was "/something/hello/" then we want the helloAction of the SomethingController.  If
// the SomethingController doesn't have a helloAction, it might have a catchAllAction.  If not,
// then the base Controller catchAllAction method can return a 404.
if ($ACTION_NAME == 'Action') {
	$ACTION_NAME = 'defaultAction';
}
if (!method_exists($CONTROLLER, $ACTION_NAME)) {
	$ACTION_NAME = 'catchAllAction';
}
else {
	array_shift($PARAMETERS);
}

call_user_func_array(array(&$CONTROLLER, $ACTION_NAME), $PARAMETERS);

if ($CONTROLLER->useCaching && isset($pageModel)) {
	$contents = trim(ob_get_clean());
	$contents = preg_replace('/>[\\r\\n\\t]+</ms', '><', $contents);
	$contents = preg_replace('/\\s+/ms', ' ', $contents);
	$pageModel->cache->set($pageCacheKey, $contents, $CONTROLLER->cacheTimeInSeconds);
	send_output($contents);
}

/**
 * Check through the appropriate entries in $CLASS_DIRS to find the class that we're trying to use.
 * @param  $className: the name of the class we're trying to use.
 */
function __autoload($className) {
	$autoloadFile = '../classes/' . $className . '.php';
	if (@include($autoloadFile)) {
		return;
	}
	include('../../' . $autoloadFile);
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
	$upperCamel = upper_camel($underscored);
	if ($upperCamel) {
		$upperCamel[0] = strtolower($upperCamel[0]);
	}
	return $upperCamel;
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
	global $URL_ROOT;
	if (!is_ajax()) {
		$session = new Session();
		if ($session->isSignedIn) {
			$pieces = preg_split('/<div id="user">.*?<\/div>/msi', $output, 2);
			if (count($pieces) > 1) {
				$output = $pieces[0] .
					'<div id="user">' .
						'<span>' . htmlentities($session->username) . '</span>' .
						'<u id="userNav">' .
							'<a href="' . $URL_ROOT . 'admin/">Admin</a>' .
							'<a href="' . $URL_ROOT . 'sign_out/" class="noAjax">Sign out</a>' .
						'</u>' .
					'</div>' .
				$pieces[1];
			}
		}
	}
	echo $output;
	exit;
}

/**
 * Whether a page was requested via AJAX
 * @return true if the page was requested via AJAX.
 */
function is_ajax() {
	return (isset($_REQUEST['isAjax']) && $_REQUEST['isAjax'])
		|| (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
			&& $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
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

