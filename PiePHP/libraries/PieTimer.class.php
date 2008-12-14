<?

class PieTimer {
	
	static function microSeconds() {
		list($unixSeconds, $seconds) = explode(' ', microtime());
		return ((float)$unixSeconds + (float)$seconds);
	}
	
	static function startTimer() {
		$GLOBALS['START_TIME'] = PieTimer::microSeconds();
	}
	
	static function logTime($label) {
		PieLogging::logLine('timer', $label.': '.(PieTimer::microSeconds() - $GLOBALS['START_TIME']).'s');
	}
	
	static function echoTime() {
		echo (PieTimer::microSeconds() - $GLOBALS['START_TIME']).'s<br>';
	}
	
}
?>