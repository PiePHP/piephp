<?

class PieSay {
	
	static function get($Text) {
		return $Text;
	}
	
	static function say($Text) {
		echo PieSay::get($Text);
	}
	
}
?>