<?php

class RefresherController extends NonCachingController {

	public function indexAction() {
		$file = $GLOBALS['REFRESHER_FILE'];
		if (!fopen($file, 'r')) {
			?>
			<script type="text/javascript">
				alert('"<?php echo addslashes($file) ?>" is not a valid refresher file.');
			</script>
			<?php
			exit;
		}
		$old = FileUtility::getModifiedTime($file);

		for ($i = 0; $i < 25; $i++) {
			sleep(1);
			$new = FileUtility::getModifiedTime($file);
			if ($new > $old) {
				$this->loadModel();
				$this->model->cacheConnect();
				$this->model->cache->flush();
				$this->renderRefreshScript('parent');
			}
		}

		$this->renderRefreshScript('window');
	}

	public function renderRefreshScript($scope) {
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
