<?php
if (!isset($GLOBALS['REQUIRED_FILES'])) {
	$GLOBALS['REQUIRED_FILES'] = array();
	require $_SERVER['DOCUMENT_ROOT'].'/../app-init/common.php';
}

$files = array(
	PIE_ROOT.'pie-documents/js/pie.js',
	PIE_ROOT.'pie-documents/js/refresher.js'
);

$GLOBALS['REQUIRED_FILES'][] = APP_ROOT.'web-documents/js/all.js.php';
while (list(, $file) = each($files)) {
	$GLOBALS['REQUIRED_FILES'][] = $file;
	require $file;
}
?>