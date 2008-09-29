<?php
if (!defined('DOCS')) {
	header('Content-Type: text/css', true);
	header('X-Pad: avoid browser bug', true);
}


$files = array(
	'/_/css/screen/base.css',
	'/_/css/screen/layout.css',
	'/_/css/screen/forms.css'
);

while (list(, $file) = each($files)) {
	$GLOBALS['REQUIRED_FILES'][] = $file;
	require $_SERVER['DOCUMENT_ROOT'].$file;
}
?>