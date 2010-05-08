<?
require $_SERVER['DOCUMENT_ROOT'] . '/../initialization/common.php';
?>

<?

PieTimer::start();

for ($i = 0; $i < 10000; $i++) {
	eval('
	/**/
	
	function pieStuffThis'.$i . '() {
		return 1;
	}
	
	function pieStuffThat'.$i . '() {
		return 2;
	}
	
	function pieStuffTheOther'.$i . '() {
		return 3;
	}
	
	function pieStuffSomethingElse'.$i . '() {
		return 2;
	}
	
	function pieStuffAnotherThing'.$i . '() {
		return 3;
	}
	
	$Something = pieStuffThis'.$i . '() + pieStuffThat'.$i . '() + pieStuffTheOther'.$i . '() + pieStuffSomethingElse'.$i . '() + pieStuffAnotherThing'.$i . '();
	');
}

PieTimer::p();

?>

<?

PieTimer::start();

for ($i = 0; $i < 10000; $i++) {
	eval('
	/*'.$i . ''.$i . ''.$i . ''.$i . '*/
	
	class PieStuff'.$i . ' {
		
		static function this() {
			return 1;
		}
		
		static function that() {
			return 2;
		}
		
		static function theOther() {
			return 3;
		}
		
		static function somethingElse() {
			return 2;
		}
		
		static function anotherThing() {
			return 3;
		}
	}
	
	$Something = PieStuff'.$i . '::this() + PieStuff'.$i . '::that() + PieStuff'.$i . '::theOther() + PieStuff'.$i . '::somethingElse() + PieStuff'.$i . '::anotherThing();
	');
}

PieTimer::p();

?>