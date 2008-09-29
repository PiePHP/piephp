<?

if ($_SERVER['SCRIPT_NAME'] == '/refresher') {
	$time = $_REQUEST['Time'] * 1;
	$files = explode(',', $_REQUEST['Files']);
	//echo $time.'<br><br>';
	//echo join(',', $files).'<br><br>';
	while (list(, $file) = each($files)) {
		//echo $modified.'<br>';
		$modified = filemtime($_SERVER['DOCUMENT_ROOT'].$file);
		if ($modified > $time) {
			echo('true');
			exit;
		}
	}
	echo('false');
	exit;
}

?>