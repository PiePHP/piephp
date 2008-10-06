<?php

$passed = 0;
$failed = 0;


class PieTests {
	
	static function getContents($path) {
		return file_get_contents(HTTP.$path.(strpos('?', $path) === false ? '?' : '&').'TEST_MODE=true');
	}
	
	static function testQuery($sql) {
		$result = mysql_query($sql) or TestFailed(mysql_error().'<pre>'.htmlentities($sql).'</pre>');
	}
	
	static function testEquality($message, $expected, $actual) {
		global $passed, $failed;
		if ($expected == $actual) {
			$passed++;
		} else {
			$failed++;
			echo '<p>'.$message.' (expected: '.$expected.', actual: '.$actual.')</p>';
		}
		
	}
	
	static function testFailed($message) {
		global $failed;
		$failed++;
		echo '<p>'.$message.'</p>';
	}
	
}
?>
