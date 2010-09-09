<?php
/**
 * Allow pages to refresh themselves when changes are made in an IDE.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class RefresherController extends NonCachingController {

	/**
	 * Process a request for the file that will call JavaScript to refresh the page.
	 */
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

	/**
	 * Render a script that will reload or refresh.
	 * @param  $scope: scope will reload the refresher if it is "window" or refresh the page if it is "parent".
	 */
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
