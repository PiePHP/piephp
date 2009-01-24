<?

class Pie {
	
	static function file($file) {
		require $GLOBALS['REQUIRED_FILES'][] = $file;
	}
}

?>
