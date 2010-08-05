<?php

class FileCache {

	var $prefix = '';

	var $expire = 3600;

	/**
	 * Point to the default cache and/or cache configuration.
	 */
	function __construct($config, $configKey = 'default') {
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
	function get($cache_key) {
		@$value = file_get_contents(APP_ROOT . 'cache/' . $this->prefix . md5($cache_key));
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
	function set($cache_key, $value) {
		file_put_contents(APP_ROOT . 'cache/' . $this->prefix . md5($cache_key), time() . ':' . $value);
	}

}
