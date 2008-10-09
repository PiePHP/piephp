<?php
require $_SERVER['DOCUMENT_ROOT'].'/../app-init/common.php';
?>

<?php

PieTimer::startTimer();

for ($i = 0; $i < 10000; $i++) {
	eval('
	/**/
	
	function pieStuffThis'.$i.'() {
		return 1;
	}
	
	function pieStuffThat'.$i.'() {
		return 2;
	}
	
	function pieStuffTheOther'.$i.'() {
		return 3;
	}
	
	function pieStuffThataoeu'.$i.'() {
		return 2;
	}
	
	function pieStuffTheOtheraoeu'.$i.'() {
		return 3;
	}
	
	$Something = pieStuffThis'.$i.'() + pieStuffThat'.$i.'() + pieStuffTheOther'.$i.'();
	');
}

PieTimer::echoTime();

?>

<?php

PieTimer::startTimer();

for ($i = 0; $i < 10000; $i++) {
	eval('
	/*'.$i.''.$i.'*/
	
	class PieStuff'.$i.' {
		
		static function this() {
			return 1;
		}
		
		static function that() {
			return 2;
		}
		
		static function theOther() {
			return 3;
		}
		
		static function thataoeu() {
			return 2;
		}
		
		static function theOtheraoeu() {
			return 3;
		}
	}
	
	$Something = PieStuff'.$i.'::this() + PieStuff'.$i.'::that() + PieStuff'.$i.'::theOther();
	');
}

PieTimer::echoTime();

?>