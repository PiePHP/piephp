<?
if (!isset($GLOBALS['REQUIRED_FILES'])) {
	$GLOBALS['REQUIRED_FILES'] = array();
	require $_SERVER['DOCUMENT_ROOT'].'/../initialization/common.php';
}

$files = array(
	PIE_ROOT.'_/js/jquery-1.2.6.js',
	PIE_ROOT.'_/js/interface.js',
	PIE_ROOT.'_/js/refresher.js'
);

$GLOBALS['REQUIRED_FILES'][] = APP_ROOT.'webpages/_/js/all.js.php';
while (list(, $file) = each($files)) {
	$GLOBALS['REQUIRED_FILES'][] = $file;
	require $file;
}
?>
