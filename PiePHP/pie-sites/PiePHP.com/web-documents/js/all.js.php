<?php
if (!defined('APP_ROOT')) {
	define('APP_ROOT', $_SERVER['DOCUMENT_ROOT'].'/../');
}

$files = array(
	APP_ROOT.'web-documents/js/pie.js',
	APP_ROOT.'web-documents/js/refresher.js'
);

$GLOBALS['REQUIRED_FILES'][] = APP_ROOT.'web-documents/js/all.js.php';
while (list(, $file) = each($files)) {
	$GLOBALS['REQUIRED_FILES'][] = $file;
	require $file;
}
?>