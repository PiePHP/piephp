<?php

class DirectoryUtility {

	public static function walk($directoryPath, $callbackName) {
		$directoryPath = rtrim($directoryPath, '/') . '/';
		$directoryHandle = opendir($directoryPath);
		$excludeArray = array('.', '..');
		while (false !== ($filename = readdir($directoryHandle))) {
			if (!in_array(strtolower($filename), $excludeArray)) {
				$filePath = $directoryPath . $filename;
				if (is_dir($filePath)) {
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