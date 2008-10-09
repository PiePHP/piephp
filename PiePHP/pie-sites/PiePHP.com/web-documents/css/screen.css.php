<?php
if (!isset($GLOBALS['REQUIRED_FILES'])) {
	header('Content-Type: text/css', true);
	header('X-Pad: avoid browser bug', true);
	$GLOBALS['REQUIRED_FILES'] = array();
	require $_SERVER['DOCUMENT_ROOT'].'/../app-init/common.php';
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