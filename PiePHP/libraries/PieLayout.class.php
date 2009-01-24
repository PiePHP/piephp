<?

ob_start();


class PieLayout {
	
	static function renderPage($pageLayout = 'default', $pageContent = array()) {
		if (ob_get_length()) {
			PieLayout::saveSlice();
		}
		Pie::file(APP_ROOT . 'layout/templates/' . $pageLayout . '.php');
	}
	
	static function pageHeader($pageContent) {
		Pie::file(APP_ROOT . 'layout/header.php');
	}
	
	static function pageFooter($pageContent) {
		Pie::file(APP_ROOT . 'layout/footer.php');
		exit;
	}
	
	static function renderSlice($nameOrPath = '', $sliceOptions = array()) {
		if (!$nameOrPath) {
			if ($buffer = $GLOBALS['bodyBuffer']) {
				echo $buffer;
				return;
			}
			$nameOrPath = preg_replace('/index\.php$/', 'body', $_SERVER['SCRIPT_NAME']);
		}
		if ($nameOrPath{0} == '/') {
			Pie::file($nameOrPath . '.php');
		} else {
			echo $GLOBALS[$nameOrPath . 'Buffer'];
		}
	}
	
	static function saveSlice($sliceName = 'body') {
		$GLOBALS[$sliceName . 'Buffer'] = ob_get_clean();
		ob_start();
	}
	
}

?>