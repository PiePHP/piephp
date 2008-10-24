<?
$count = $_REQUEST['count'] * 1;
if ($count % 10 == 0) {
	exec('cd ' . SVN_ROOT . '; svn update');
}

$time = $_REQUEST['time'] * 1;
$files = explode(',', $_REQUEST['files']);
while (list(, $file) = each($files)) {
	$modified = filemtime($file);
	if ($modified > $time) {
		die('true');
	}
}
die('false');

?>