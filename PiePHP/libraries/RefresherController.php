<?php

class RefresherController extends Controller {

	function index()
	{
		$file = 'C:/Program Files/Macromedia/HomeSite+/AutoBackup/AutoBackup.ini';

		$handle = fopen($file, 'r');
		$stat = fstat($handle);
		$old = $stat['mtime'];

		for ($i = 0; $i < 29; $i++) {
			sleep(1);
			$handle = fopen($file, 'r');
			$stat = fstat($handle);
			$new = $stat['mtime'];
			if ($new > $old) {
				$this->refresh('parent');
			}
		}
		
		$this->refresh('window');
	}

	function refresh($scope) {
		?>
		<html>
		<head><title>Refresher</title></head>
		<body>
		<script type="text/javascript">
			<?=$scope?>.location.reload();
		</script>
		</body>
		</html>
		<?
		exit;
	}
}
