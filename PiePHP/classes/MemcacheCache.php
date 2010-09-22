<?php
/**
 * Read and write data to Memcache.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2007-2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class MemcacheCache {

	/**
	 * The connection to memcache.
	 */
	public $connection;

	/**
	 * A prefix for the cache keys, to avoid overlaps with another MemcacheCache.
	 */
	public $prefix = '';

	/**
	 * Set cached values to expire in a certain number of seconds.
	 */
	public $expire = 3600;

	/**
	 * Point to the default cache and/or cache configuration.
	 * @param  $config: an associative array of configuration parameters.
	 * @param  $configName: the configuration comes from $CACHES[$configName].
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
	 * @param  the message to be logged.
	 */
	public function triggerError($message) {
		trigger_error($message, E_USER_ERROR);
	}

	/**
	 * Get a value from Memcache by its key.
	 * If we previously failed to make a connection, we can just move on.
	 * @param  $cacheKey: the key (in conjunction with the prefix) is used to retrieve the value.
	 * @return the cached value if one was found.
	 */
	public function get($cacheKey) {
		if ($this->connection) {
			$value = $this->connection->get($this->prefix . $cacheKey);
			return $value;
		}
	}

	/**
	 * Store a value in Memcache.
	 * If we previously failed to make a connection, we can just move on.
	 * @param  $cacheKey: the key (in conjunction with the prefix) is used to name the file.
	 * @param  $value: the value is written to the file, along with the current time for expiration purposes.
	 * @param  $expire: how long to keep the cached value (in seconds).  If null, we'll use the default.
	 */
	public function set($cacheKey, $value, $expire = NULL) {
		if ($this->connection) {
			$this->connection->set($this->prefix . $cacheKey, $value, 0, $expire === NULL ? $this->expire : $expire);
		}
	}

	/**
	 * Flush all values from Memcache.
	 */
	public function flush() {
		$this->connection->flush();
	}

	/**
	 * Get stats from Memcache.
	 * @return the stats from Memcache.
	 */
	public function getStats() {
		return $this->connection->getStats();
	}

}
