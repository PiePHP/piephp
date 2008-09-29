<?php

class PieRefresher {
	
	static function requireFile($file) {
		require $GLOBALS['REQUIRED_FILES'][] = $file;
	}
	
	static function render() {
		ob_start();
		require DOCS.'/_/js/all.js.php';
		require DOCS.'/_/css/screen.css.php';
		$code = ob_get_clean();
		preg_match_all('/url\(([a-z\/0-9\.\-]+)\)/', $code, $matches);
		while (list(, $file) = each($matches[1])) {
			$GLOBALS['REQUIRED_FILES'][] = preg_replace('/^[\/]?img/', '/_/img', $file);
		}
		?>
		<script type="text/javascript">
		Refresher.start(<?=time()?>, ['<?=join("', '", $GLOBALS['REQUIRED_FILES'])?>']);
		</script>
		<?php
	}
	
}

?>
