<?php
/**
 * Helper methods for dealing with directories.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2007-2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class DirectoryUtility {

	/**
	 * Walk through a directory and its subdirectories calling a given function on each file.
	 * @param  $directoryPath: the parent path to start walking from.
	 * @param  $callbackName: the function to call on each file.
	 * @param  $useRecursion: whether to walk through subdirectories as well.
	 * @return true if we can keep on walking through the directory (because the callback function has been returning true).
	 */
	public static function walk($directoryPath, $callbackName, $useRecursion = true) {
		$directoryPath = rtrim($directoryPath, '/') . '/';
		$directoryHandle = opendir($directoryPath);
		$excludeArray = array('.', '..');
		while (false !== ($filename = readdir($directoryHandle))) {
			if (!in_array(strtolower($filename), $excludeArray)) {
				$filePath = $directoryPath . $filename;
				if (is_dir($filePath) && $useRecursion) {
					if (!DirectoryUtility::walk($filePath, $callbackName)) {
						return false;
					}
				}
				else {
					if (!$callbackName($filePath)) {
						return false;
					}
				}
			}
		}
		return true;
	}

}