<?php

class FileUtility {

	public static function getModifiedTime($path) {
		$handle = fopen($path, 'r');
		$stat = fstat($handle);
		return $stat['mtime'];
	}

}