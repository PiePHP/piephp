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

class RefresherController extends Controller {

	/**
	 * Load the refresher script via AJAX in an IFrame.
	 * Putting it in an IFrame makes it run even if there are JavaScript errors on the parent page.
	 */
	public function defaultAction() {
		global $URL_ROOT;
		?>
		<html>
		<head>
			<title>Refresher</title>
			<script type="text/javascript" src="/js/jquery-1.4.2.js"></script>
		</head>
		<body>
			<script type="text/javascript">
				setTimeout(function() {
					$('body').load('<?php echo $URL_ROOT; ?>refresher/script/' + (new Date()).getTime());
				}, 500);
				setTimeout(window.location.reload, 30000);
			</script>
		</body>
		</html>
		<?php
	}

	/**
	 * Echo some code wrapped in script tags.
	 * @param  $code: a snippet of JavaScript code.
	 */
	public function renderScript($code) {
		echo '<script type="text/javascript">' . $code . '</script>';
	}

	/**
	 * Check the refresher file every second until we see a change or 10 minutes has passed.
	 * If we see a change, we can tell the parent page to refresh, otherwise we just reload the refresher frame.
	 */
	public function scriptAction() {
		global $REFRESHER_FILE;
		global $CACHES;
		$this->preventCaching();
		$file = $REFRESHER_FILE;
		if (!fopen($file, 'r')) {
			$this->renderScript("alert('$file is not a valid refresher file.')");
			exit;
		}
		$old = FileUtility::getModifiedTime($file);

		// The PHP default is to allow 30 seconds for processing, so we'll give it 25 iterations of a 1-second-sleep loop.
		for ($i = 0; $i < 25; $i++) {
			$new = FileUtility::getModifiedTime($file);

			// If the new file's modified date is more recent than the old one's, we can refresh.
			if ($new > $old) {

				// Flushing the caches ensures that we'll see the newest code even if we're on caching pages.
				foreach ($CACHES as $cacheName => $cacheConfig) {

					// Ensure a new model is loaded by resetting the model and then loading it.
					$this->model = NULL;
					$this->loadModel();

					// Load cache the into the model by name.
					// The loader will find its config in the global $CACHES array.
					$cache = $this->model->loadCache($cacheName);

					// Flush the cache.
					$cache->flush();

				}

				// The refresher script is in a frame, so in order to reload the page it's on, we must reload the parent.
				$this->renderScript('parent.location.reload()');
				exit;
			}

			// Wait for 1 second before checking modified dates again.
			sleep(1);

		}
		// The refresher script is in a frame, so reloading the window will just reload the refresher.
		$this->renderScript('window.location.reload()');
	}

}
