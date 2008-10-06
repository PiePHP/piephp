<?php
if (!defined('DOCS')) {
	header('Content-Type: text/css', true);
	header('X-Pad: avoid browser bug', true);
	define('DOCS', $_SERVER['DOCUMENT_ROOT']);
}

$files = array(
	DOCS.'/_/css/screen/base.css',
	DOCS.'/_/css/screen/layout.css',
	DOCS.'/_/css/screen/text.css',
	DOCS.'/_/css/screen/forms.css'
);

$GLOBALS['REQUIRED_FILES'][] = DOCS.'/_/css/screen.css.php';
while (list(, $file) = each($files)) {
	$GLOBALS['REQUIRED_FILES'][] = $file;
	require $file;
}
?>