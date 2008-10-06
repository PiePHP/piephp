<?php
if (!defined('DOCS')) {
	define('DOCS', $_SERVER['DOCUMENT_ROOT']);
}

$files = array(
	DOCS.'/_/js/pie.js',
	DOCS.'/_/js/refresher.js'
);

$GLOBALS['REQUIRED_FILES'][] = DOCS.'/_/js/all.js.php';
while (list(, $file) = each($files)) {
	$GLOBALS['REQUIRED_FILES'][] = $file;
	require $file;
}
?>