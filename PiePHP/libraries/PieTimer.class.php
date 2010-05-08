<?

class PieTimer {
	
	static function microSeconds() {
		list($unixSeconds, $seconds) = explode(' ', microtime());
		return ((float)$unixSeconds + (float)$seconds);
	}
	
	static function start($code = '') {
		$GLOBALS['t0:' . $code] = PieTimer::microSeconds();
	}
	
	static function check($code = '') {
		return PieTimer::microSeconds() - $GLOBALS['t0:' . $code];
	}
	
	static function finish($code = '') {
		$time = PieTimer::check($code);
		unset($GLOBALS['t0:' . $code]);
		return $time;
	}
	
	static function log($code = '') {
		PieLogging::log('timer', $code . ': ' . PieTimer::finish($code) . 's');
	}
	
	static function p($code = '') {
		Pie::p(PieTimer::finish($code) . 's');
	}
	
}
?>