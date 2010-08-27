<?php

class FileCache {

	public $prefix = '';

	public $expire = 3600;

	/**
	 * Point to the default cache and/or cache configuration.
	 */
	function __construct($config, $configName = 'default') {
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
	 */
	function get($cacheKey) {
		@$value = file_get_contents(APP_ROOT . 'cache/' . $this->prefix . md5($cacheKey));
		if ($value) {
			list($time, $value) = explode(':', $value, 2);
			if (time() - $time > $this->expire) {
				$value = '';
			}
		}
		return $value;
	}

	/**
	 * Store a value in a file.
	 */
	function set($cacheKey, $value) {
		global $APP_ROOT;
		file_put_contents($APP_ROOT . 'cache/' . $this->prefix . md5($cacheKey), time() . ':' . $value);
	}

}
