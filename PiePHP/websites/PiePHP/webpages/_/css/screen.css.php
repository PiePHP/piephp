<?
if (!isset($GLOBALS['REQUIRED_FILES'])) {
	header('Content-Type: text/css', true);
	header('X-Pad: avoid browser bug', true);
	$GLOBALS['REQUIRED_FILES'] = array();
	require $_SERVER['DOCUMENT_ROOT'].'/../initialization/common.php';
}

$files = array(
	APP_ROOT.'webpages/_/css/base.css',
	APP_ROOT.'webpages/_/css/layout.css',
	APP_ROOT.'webpages/_/css/text.css',
	APP_ROOT.'webpages/_/css/forms.css',
	APP_ROOT.'webpages/_/css/dock.css'
);

$GLOBALS['REQUIRED_FILES'][] = APP_ROOT.'webpages/_/css/screen.css.php';
while (list(, $file) = each($files)) {
	$GLOBALS['REQUIRED_FILES'][] = $file;
	require $file;
}
?>