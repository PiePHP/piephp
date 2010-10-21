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
	 * If the refresher file exists, return it in a singleton array.
	 * Otherwise, return an array of the 10 most recently modified files.
	 */
	public function getFilesToCheck() {
		global $PIE_DIR;
		global $REFRESHER_FILE;
		global $REFRESHER_FILES;

		if (isset($REFRESHER_FILE) && file_exists($REFRESHER_FILE)) {
			return array($REFRESHER_FILE);
		}
		else {
			$REFRESHER_FILES = array();

			/**
			 * Add a file to an associative array of file paths and modified times.
			 * @param  $path: the path of the file to check.
			 * @return true to continue.
			 */
			function addFile($path) {
				global $REFRESHER_FILES;
				if (strpos($path, '/.') === false
					&& substr($path, -4) != '.log'
					&& substr($path, -11) != '.cache.html') {
					// Add the file and its modified time.
					$REFRESHER_FILES[$path] = FileUtility::getModifiedTime($path);
				}
				// Keep walking.
				return true;
			}

			// Walk through the Pie directory looking for the most recently modified files.
			DirectoryUtility::walk($PIE_DIR, 'addFile');

			// Sort files in descending order of modified time.
			arsort($REFRESHER_FILES);

			// Return the 10 most recently modified files.
			return array_keys(array_slice($REFRESHER_FILES, 0, 10));
		}
	}

	/**
	 * Get the maximum modified time among an array of files.
	 * @param  $files: the array of files whose modified times we want to check.
	 */
	public function getMaxModifiedTime($files) {
		$maxTime = 0;
		foreach ($files as $file) {
			$maxTime = max($maxTime, FileUtility::getModifiedTime($file));
		}
		return $maxTime;
	}

	/**
	 * Check the refresher file every second until we see a change or 10 minutes has passed.
	 * If we see a change, we can tell the parent page to refresh, otherwise we just reload the refresher frame.
	 */
	public function scriptAction() {
		global $CACHES;
		$this->preventCaching();

		$filesToCheck = $this->getFilesToCheck();

		// The last modified time can be passed in to avoid missing a modification between refresher requests.
		if (isset($_GET['m'])) {
			$modifiedTime = $_GET['m'] * 1;
		}
		else {
			$modifiedTime = $this->getMaxModifiedTime($filesToCheck);
		}

		// The PHP default is to allow 30 seconds for processing, so we'll give it 25 iterations of a 1-second-sleep loop.
		for ($i = 0; $i < 25; $i++) {
			$newModifiedTime = $this->getMaxModifiedTime($filesToCheck);

			// If the new file's modified date is more recent than the old one's, we can refresh.
			if ($newModifiedTime > $modifiedTime) {

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
		$this->renderScript('window.location = window.location.href.replace(/\?.*$/, "") + "?m=' . $modifiedTime . '"');
	}

}
