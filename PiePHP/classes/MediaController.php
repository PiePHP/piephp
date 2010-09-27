<?php
/**
 * This controller is used to join multiple JavaScript files or multiple CSS files into a
 * single minified file to make media requests few and small.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

abstract class MediaController extends Controller {

	/**
	 * This fileType should be "css" or "js".
	 */
	public $fileType;

	/**
	 * This contentType should be "text/css" or "application/javascript".
	 */
	public $contentType;

	/**
	 * Take a request for a file, and assemble the component files.
	 * @param  $file: The name of the files that is being requested (e.g. "core-0.0.1.js").
	 */
	public function catchAllAction($file) {
		global $VERSION;
		global $URL_PATH;
		global $SITE_DIR;
		global $ENVIRONMENT;

		// If a version string is in the request, we can cache JS and CSS for a very long time.
		// If we need the client to get a new version, we just update our version string.
		if (strpos($URL_PATH, $VERSION)) {
			$this->cacheTimeInSeconds = 86400000; // Cache for 1000 days on the server.
			header('Expires: Mon, 31 Dec 2099 23:59:59 GMT'); // Party like it's 2099.
		}
		header('Content-type: ' . $this->contentType);

		// Include the configuration which specifies the groups of CSS and JavaScript files.
		include $SITE_DIR . 'mediaConfig.php';

		$parts = explode('.', $file);
		$extension = array_pop($parts);
		list($groupName) = explode('-', $parts[0]);

		$groups = $MEDIA_GROUPS[$this->fileType];

		if (isset($groups[$groupName])) {
			$groupFiles = $groups[$groupName];
		}
		else {
			$groupFiles = array($file);
		}

		$this->renderFileHeader($groupName);
		$this->renderFileContents($groupFiles);
		$this->renderFileFooter();

		$contents = ob_get_contents();
		ob_flush();

		if ($ENVIRONMENT != 'development') {
			$contents = $this->minify($contents);
			file_put_contents($SITE_DIR . 'public/' . $file, $contents);
		}
	}

	/**
	 * By default, don't put a header on a media file.
	 * Some media types will have headers, and some won't.
	 */
	public function renderFileHeader() {
	}

	/**
	 * Go through the files in the group, and render their contents.
	 * In a development environment, we should comment which file is which.
	 * In a staging or production environment, we should prepare for minification.
	 * @param  $groupFiles: An array of JS or CSS filenames that come from a specific group in "mediaConfig.php". 
	 */
	public function renderFileContents($groupFiles) {
		global $SITE_DIR;
		global $ENVIRONMENT;

		foreach ($groupFiles as $file) {
			$filePath = $SITE_DIR . 'public/' . $this->fileType . '/' . $file;
			$contents = file_get_contents($filePath);

			if ($ENVIRONMENT == 'development') {
				// Indicate which file follows, for debugging purposes.
				echo "/* renderFileContents: $file */\r\n";
			}
			else {
				// Remove the first comment which could contain PiePHP license messages that we don't need.
				$contents = preg_replace('/^\/\*.*?\*\//ms', '', trim($contents));
			}

			echo $contents;
		}
	}

	/**
	 * By default, don't put a footer on a media file.
	 * Some media types will have footers, and some won't.
	 */
	public function renderFileFooter() {
	}

}
