<?php
$files = array(
	'/_/js/jquery-1.2.6.js',
	'/_/js/pie.js',
	'/_/js/ready.js',
	'/_/js/refresher.js'
);

while (list(, $file) = each($files)) {
	$GLOBALS['REQUIRED_FILES'][] = $file;
	require $_SERVER['DOCUMENT_ROOT'].$file;
	echo "\n";
}
?>