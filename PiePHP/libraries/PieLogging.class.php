<?php

class PieLogging {
	
	static function logLine($logName, $line, $maxLength = 0) {
		if ($maxLength) {
			$line = preg_replace('/[\\r\\n]+/', ' ', $line);
			if (strlen($line) > $maxLength) {
				$line = substr($line, 0, $maxLength);
			}
		}
		$handle = fopen( APP_ROOT.'webpages/_/log/all.log', 'a');
		fwrite($handle, $line."\r\n");
		fclose($handle);
		$handle = fopen( APP_ROOT.'webpages/_/log/'.$logName.'.log', 'a');
		fwrite($handle, $line."\r\n");
		fclose($handle);
	}
	
	static function debug($line, $maxLength = 0) {
		LogLine('debug', $line, $maxLength);
	}
	
	static function error($line, $maxLength = 0) {
		LogLine('error', $line, $maxLength);
	}
	
	static function pre($value) {
		echo '<pre>';
		if (is_string($value)) {
			echo htmlentities($value);
		}
		else {
			print_r($value);
		}
		echo '</pre>';
	}
	
}
?>