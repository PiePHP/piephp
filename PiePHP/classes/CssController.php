<?php
/**
 * This controller is used to join multiple CSS files into a
 * single minified file to make media requests few and small.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class CssController extends MediaController {

	public $fileType = 'css';

	public $contentType = 'text/css';

	public function minify($contents) {
		$contents = trim($contents);
		$contents = preg_replace('/\/\*.*?\*\//ms', '', $contents);
		$contents = preg_replace('/\\s*([,:;\{\}])\\s*/ms', '$1', $contents);
		$contents = str_replace(';}', '}', $contents);
		$contents = trim($contents);
		return $contents;
	}

}
