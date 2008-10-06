<?
$time = $_REQUEST['Time'] * 1;
$files = explode(',', $_REQUEST['Files']);
while (list(, $file) = each($files)) {
	$modified = filemtime($file);
	if ($modified > $time) {
		die('true');
	}
}
die('false');

?>