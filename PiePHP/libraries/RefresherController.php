<?php

class RefresherController extends Controller {
	
	public $isCacheable = false;

	function indexAction() {
		$file = $GLOBALS['REFRESHER_FILE'];

		$handle = fopen($file, 'r');
		$stat = fstat($handle);
		$old = $stat['mtime'];

		for ($i = 0; $i < 25; $i++) {
			sleep(1);
			$handle = fopen($file, 'r');
			if (!$handle) {
				?>
				<script type="text/javascript">
					alert('"<?php echo addslashes($file) ?>" is not a valid refresher file.');
				</script>
				<?php
				exit;
			}
			$stat = fstat($handle);
			$new = $stat['mtime'];
			if ($new > $old) {
				$this->loadModel();
				$this->model->cacheConnect();
				$this->model->cache->flush();
				$this->renderRefreshScript('parent');
			}
		}

		$this->renderRefreshScript('window');
	}

	function renderRefreshScript($scope) {
		?>
		<html>
		<head><title>Refresher</title></head>
		<body>
		<script type="text/javascript">
			<?php echo $scope; ?>.location.reload();
		</script>
		</body>
		</html>
		<?php
		exit;
	}
}
