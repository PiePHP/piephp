<?

class Pie {
	
	static function file($file) {
		require $GLOBALS['REQUIRED_FILES'][] = $file;
	}
	
	static function p($data) {
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}
	
}

?>
