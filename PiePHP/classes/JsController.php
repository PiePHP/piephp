<?php
/**
 * This controller is used to join multiple JavaScript files into a
 * single minified file to make media requests few and small.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class JsController extends MediaController {

	public $fileType = 'js';

	public $contentType = 'application/javascript';

	/**
	 * Include a JavaScript header that will prevent re-running this script and allow all variables
	 * in the component scripts to be part of the same closure.
	 * @param  $groupName: the name of the file that we're grouping.
	 */
	public function renderFileHeader($groupName) {
		echo 'window.' . $groupName . 'JS || (function(window, document, location) {';
		echo 'window.' . $groupName . 'JS = 1;';
	}

	/**
	 * Close and call the function that began in the header.
	 */
	public function renderFileFooter() {
		echo '})(window, document, location);';
	}

	public function minify($contents) {

		// Use Google Closure Compiler to minify.
		$response = HttpUtility::post('http://closure-compiler.appspot.com/compile', array(
			'js_code' => $contents,
			'compilation_level' => 'SIMPLE_OPTIMIZATIONS',
			'output_format' => 'text',
			'output_info' => 'compiled_code'
		));

		if ($response) {
			list($headers, $contents) = explode("\r\n\r\n", $response, 2);
		}

		return $contents;
	}

}

