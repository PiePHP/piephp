<?php
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