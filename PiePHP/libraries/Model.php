<?php

class Model {

	var $databaseConfigKey = 'default';
	var $database = NULL;

	var $cacheConfigKey = 'default';
	var $cache = NULL;

	/**
	 * Connect to the database if a connection has not already been made.
	 */
	function databaseConnect() {
		if (!$this->database && $this->databaseConfigKey) {
			$databaseConfigs = $GLOBALS['DATABASES'];
			$config = $databaseConfigs[$this->databaseConfigKey];
			if ($config['type'] == 'mysql') {
				$this->database = new MysqlDatabase($config, $this->databaseConfigKey);
			}
		}
		return $this->database;
	}

	/**
	 * Connect to the cache if a connection has not already been made.
	 */
	function cacheConnect() {
		if (!$this->cache && $this->cacheConfigKey) {
			$cacheConfigs = $GLOBALS['CACHES'];
			$config = $cacheConfigs[$this->cacheConfigKey];
			if ($config['type'] == 'memcache') {
				$this->cache = new MemcacheCache($config, $this->cacheConfigKey);
			}
			elseif ($config['type'] == 'file') {
				$this->cache = new FileCache($config, $this->cacheConfigKey);
			}
		}
		return $this->cache;
	}

	/**
	 * Query the cache and/or database, and return results as an array of associative arrays.
	 */
	function results($sql, $cacheTimeInSeconds = false) {
		// Try getting cached results.
		if ($cacheTimeInSeconds !== false && $this->cacheConnect()) {
			$cache_key = strlen($sql) < 255 ? $sql : md5($sql);
			$value = $this->cache->get($cache_key);
			if ($value) {
				return unserialize($value);
			}
		}
		else {
			$cacheTimeInSeconds = false;
		}
		// No results from the cache, so connect to the database and get them.
		$this->databaseConnect();
		$results = $this->database->results($sql);
		// Try caching results for next time.
		if ($cacheTimeInSeconds !== false) {
			$this->cache->set($cache_key, serialize($results), $cacheTimeInSeconds);
		}
		return $results;
	}

	/**
	 * Select results from the database or cache.
	 */
	function select($sql, $cacheTimeInSeconds = false) {
		return $this->results('SELECT ' . $sql, $cacheTimeInSeconds);
	}

}
