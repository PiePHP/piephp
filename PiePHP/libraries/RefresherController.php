<?php
/**
 * Allow pages to refresh themselves in a development ENVIRONMENT when changes are made.
 * The developer's IDE needs to log changes into a file, which is pointed to by $REFRESHER_FILE in the local config.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class RefresherController extends NonCachingController {

	/**
	 * Load the refresher script via AJAX in an IFrame.
	 * Putting it in an IFrame makes it run even if there are JavaScript errors on the parent page.
	 */
	public function indexAction() {
		?>
		<html>
		<head>
			<title>Refresher</title>
			<script type="text/javascript" src="/media/jquery-1.4.2.js"></script>
		</head>
		<body>
			<script type="text/javascript">
				setTimeout(function() {
					$.get('<?php echo $GLOBALS['DISPATCHER_PATH']; ?>refresher/script', function(js) {
						eval(js);
					});
				}, 1000);
			</script>
		</body>
		</html>
		<?php
	}

	/**
	 * Check the refresher file every second until we see a change or 10 minutes has passed.
	 * If we see a change, we can tell the parent page to refresh, otherwise we just reload the refresher frame.
	 */
	public function scriptAction() {
		$file = $GLOBALS['REFRESHER_FILE'];
		if (!fopen($file, 'r')) {
			//$this->renderScriptStart();
			echo "alert('$file is not a valid refresher file.')";
			//$this->renderScriptEnd();
			exit;
		}
		$old = FileUtility::getModifiedTime($file);

		// The PHP default is to allow 30 seconds for processing, so we'll give it 25 iterations of a 1-second-sleep loop.
		for ($i = 0; $i < 25; $i++) {
			sleep(1);
			$new = FileUtility::getModifiedTime($file);
			if ($new > $old) {

				// Flushing the cache ensures that we'll see the newest code even if we're on a cached page.
				$this->loadModel()->cacheConnect()->flush();

				$this->renderRefreshScript('parent');
				exit;
			}
		}

		$this->renderRefreshScript('window');
	}

	/**
	 * Render a script that will reload the refresher or refresh the page.
	 * @param  $scope: if scope is "window", the refresher frame will reload, or if it's "parent" the whole page will refresh.
	 */
	public function renderRefreshScript($scope) {
		echo "$scope.location.reload()";
	}
}
