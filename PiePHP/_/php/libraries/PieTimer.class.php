<?php

class PieTimer {
	
	static function microSeconds() {
		list($unixSeconds, $seconds) = explode(' ', microtime());
		return ((float)$unixSeconds + (float)$seconds);
	}
	
	static function startTimer() {
		$GLOBALS['START_TIME'] = MicroSeconds();
	}
	
	static function logTime($label) {
		LogLine('timer', $label.': '.(MicroSeconds() - $GLOBALS['START_TIME']).'s');
	}
	
}
?>