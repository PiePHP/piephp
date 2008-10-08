<?php

ob_start();


class PieLayout {
	
	static function renderPage($pageLayout = 'default', $pageContent = array()) {
		if (ob_get_length()) {
			PieLayout::saveSliceContent();
		}
		PieRefresher::requireFile(APP_ROOT.'app-layout/templates/'.$pageLayout.'.php');
	}
	
	static function pageHeader($pageContent) {
		PieRefresher::requireFile(APP_ROOT.'app-layout/header.php');
	}
	
	static function pageFooter($pageContent) {
		PieRefresher::requireFile(APP_ROOT.'app-layout/footer.php');
		exit;
	}
	
	static function renderSlice($nameOrPath = '', $sliceOptions = array()) {
		if (!$nameOrPath) {
			if ($buffer = $GLOBALS['BodyBuffer']) {
				echo $buffer;
				return;
			}
			$nameOrPath = preg_replace('/index\.php$/', 'body', $_SERVER['SCRIPT_NAME']);
		}
		if ($nameOrPath{0} == '/') {
			PieRefresher::requireFile($nameOrPath.'.php');
		} else {
			echo $GLOBALS[$nameOrPath.'Buffer'];
		}
	}
	
	static function saveSliceContent($sliceName = 'Body') {
		$GLOBALS[$sliceName.'Buffer'] = ob_get_clean();
		ob_start();
	}
	
}

?>