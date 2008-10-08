<?php
if (!defined('APP_ROOT')) {
	header('Content-Type: text/css', true);
	header('X-Pad: avoid browser bug', true);
	define('APP_ROOT', $_SERVER['DOCUMENT_ROOT'].'/../');
}

$files = array(
	APP_ROOT.'web-documents/css/base.css',
	APP_ROOT.'web-documents/css/layout.css',
	APP_ROOT.'web-documents/css/text.css',
	APP_ROOT.'web-documents/css/forms.css'
);

$GLOBALS['REQUIRED_FILES'][] = APP_ROOT.'web-documents/css/screen.css.php';
while (list(, $file) = each($files)) {
	$GLOBALS['REQUIRED_FILES'][] = $file;
	require $file;
}
?>