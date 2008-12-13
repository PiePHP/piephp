<?php

class PieRefresher {
	
	static function requireFile($file) {
		require $GLOBALS['REQUIRED_FILES'][] = $file;
	}
	
	static function render() {
		ob_start();
		require APP_ROOT.'webpages/_/js/all.js.php';
		require APP_ROOT.'webpages/_/css/screen.css.php';
		$code = ob_get_clean();
		preg_match_all('/url\(([a-z\/0-9\.\-]+)\)/', $code, $matches);
		while (list(, $file) = each($matches[1])) {
			$GLOBALS['REQUIRED_FILES'][] = preg_replace('/^[\/]?img/', '/img', $file);
		}
		?>
		<script type="text/javascript">
		Refresher.setup(<?=time()?>, ['<?=join("', '", $GLOBALS['REQUIRED_FILES'])?>']);
		</script>
		<?php
	}
	
}

?>
