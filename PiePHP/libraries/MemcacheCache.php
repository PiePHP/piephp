<?php

class MemcacheCache {

	public $connection;

	public $prefix = '';

	public $expire = 3600;

	/**
	 * Point to the default cache and/or cache configuration.
	 */
	function __construct($config, $configKey = 'default') {
		$this->connection = memcache_pconnect($config['host'], $config['port'])
			or $this->error('Could not connect to ' . $configKey . ' cache.');
		if (isset($config['prefix'])) {
			$this->prefix = $config['prefix'];
		}
		if (isset($config['expire'])) {
			$this->expire = $config['expire'];
		}
	}

	/**
	 * Show or log an error.
	 */
	function error($message) {
		if (ini_get('display_errors')) {
			die($message);
		}
		else {
			error_log($sql) and die();
		}
	}

	/**
	 * Get a value from Memcache by its key.
	 */
	function get($cache_key) {
		$value = $this->connection->get($this->prefix . $cache_key);
		return $value;
	}

	/**
	 * Store a value in Memcache.
	 */
	function set($cache_key, $value, $expire = NULL) {
		$this->connection->set($this->prefix . $cache_key, $value, 0, $expire === NULL ? $this->expire : $expire);
	}

}
