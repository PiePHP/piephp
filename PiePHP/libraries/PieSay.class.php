<?

class PieSay {
	
	static function get($text) {
		return $text;
	}
	
	static function say($text) {
		echo PieSay::get($text);
	}
	
}
?>