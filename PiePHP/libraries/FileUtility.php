<?php
/**
 * The FileUtility provides helper methods for operating on files.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class FileUtility {

	/**
	 * Get the time the file was last modified.
	 * @param  $path: the file path to check.
	 * @return the last modified time.
	 */
	public static function getModifiedTime($path) {
		$handle = fopen($path, 'r');
		$stat = fstat($handle);
		return $stat['mtime'];
	}

}