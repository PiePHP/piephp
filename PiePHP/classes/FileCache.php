<?php
/**
 * Read and write data from cache files.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class FileCache {

	/**
	 * Prefix the files to avoid overlaps with another FileCache.
	 */
	public $prefix = '';

	/**
	 * Set cached files to expire in a certain number of seconds.
	 */
	public $expire = 3600;

	/**
	 * Point to the default cache and/or cache configuration.
	 * @param  $config: an associative array of configuration parameters.
	 * @param  $configName: the configuration comes from $CACHES[$configName].
	 */
	public function __construct($config, $configName = 'default') {
		$this->prefix = $config['prefix'];
		if (isset($config['prefix'])) {
			$this->prefix = $config['prefix'];
		}
		if (isset($config['expire'])) {
			$this->expire = $config['expire'];
		}
	}

	/**
	 * Get a value from a file.
	 * @param  $cacheKey: the key (in conjunction with the prefix) is used to locate the file.
	 * @return the cached value if one was found.
	 */
	public function get($cacheKey) {
		$path = $this->getPath($cacheKey);
		if (file_exists($path)) {
			@$value = file_get_contents($path);
			if ($value) {
				list($time, $value) = explode(':', $value, 2);
				if (time() - $time > $this->expire) {
					$value = '';
				}
				return $value;
			}
		}
	}

	/**
	 * Store a value in a file.
	 * @param  $cacheKey: the key (in conjunction with the prefix) is used to name the file.
	 * @param  $value: the value is written to the file, along with the current time for expiration purposes.
	 */
	public function set($cacheKey, $value) {
		$path = $this->getPath($cacheKey);
		file_put_contents($path, time() . ':' . $value);
	}

	/**
	 * Get the file path for a cached item.
	 * @param  $cacheKey: the key (in conjunction with the prefix) is used to name the file.
	 */
	public function getPath($cacheKey) {
		global $SITE_DIR;
		return $SITE_DIR . 'cache/' . $this->prefix . md5($cacheKey) . '.html';
	}

	/**
	 * "Flush" the cache by deleting all cache files.
	 */
	public function flush() {
		global $SITE_DIR;
		$directoryHandle = opendir($SITE_DIR . 'cache');
		while (false !== ($filename = readdir($directoryHandle))) {
			if (substr($filename, 0, strlen($this->prefix)) == $this->prefix) {
				unlink($SITE_DIR . 'cache/' . $filename);
			}
		}
	}

	/**
	 * Get stats on cache files.
	 * @return the stats from Memcache.
	 */
	public function getStats() {
		global $SITE_DIR;
		$files = array();
		$directoryHandle = opendir($SITE_DIR . 'cache');
		while (false !== ($filename = readdir($directoryHandle))) {
			if (substr($filename, 0, strlen($this->prefix)) == $this->prefix) {
				$files[] = $SITE_DIR . 'cache/' . $filename;
			}
		}
		return array('files' => count($files));
	}

}
