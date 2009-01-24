<?
$REQUIRED_FILES = array();
require $_SERVER['DOCUMENT_ROOT'] . '/../initialization/common.php';

$count = $_REQUEST['count'] * 1;
if ($count % 10 == 0) {
	exec('cd ' . SVN_ROOT . '; svn update');
}

$time = $_REQUEST['time'] * 1;
$files = explode(',', $_REQUEST['files']);
//$files = array('C:/PiePHP/pie-sites/PiePHP.com/webpages/index.php');
$i = 0;
while (list(, $file) = each($files)) {
	if ($file[0] == '/') {
		$file = $_SERVER['DOCUMENT_ROOT'] . $file;
	}
	$modified = filemtime($file);
	if ($modified > $time) {
		die('true');
	}
	$i++;
}
die('false');

?>