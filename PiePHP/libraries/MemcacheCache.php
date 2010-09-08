<?php

class MemcacheCache {

	public $connection;

	public $prefix = '';

	public $expire = 3600;

	/**
	 * Point to the default cache and/or cache configuration.
	 */
	public function __construct($config, $configName = 'default') {
		$this->connection = memcache_pconnect($config['host'], $config['port'])
			or $this->triggerError('Could not connect to ' . $configName . ' cache.');
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
	public function triggerError($message) {
		trigger_error($message, E_USER_ERROR);
	}

	/**
	 * Get a value from Memcache by its key.
	 */
	public function get($cacheKey) {
		$value = $this->connection->get($this->prefix . $cacheKey);
		return $value;
	}

	/**
	 * Store a value in Memcache.
	 */
	public function set($cacheKey, $value, $expire = NULL) {
		$this->connection->set($this->prefix . $cacheKey, $value, 0, $expire === NULL ? $this->expire : $expire);
	}

	/**
	 * Store a value in Memcache.
	 */
	public function flush() {
		$this->connection->flush();
	}

}
