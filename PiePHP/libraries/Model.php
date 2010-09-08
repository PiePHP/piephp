<?php

class Model {

	public $databaseConfigName = 'default';
	public $database = NULL;

	public $cacheConfigName = 'default';
	public $cache = NULL;

	/**
	 * Get the driver type and config array from a config string.
	 */
	public function getTypeAndConfig($configString) {
		list($type, $string) = explode(':', $configString, 2);
		$pairs = explode(' ', $string);
		$config = array();
		foreach ($pairs as $pair) {
			list($key, $value) = explode('=', $pair, 2);
			$config[$key] = $value;
		}
		return array($type, $config);
	}

	/**
	 * Connect to the database if a connection has not already been made.
	 */
	public function databaseConnect() {
		global $DATABASES;
		if (!$this->database && $this->databaseConfigName) {
			$database = $DATABASES[$this->databaseConfigName];
			if (is_string($database)) {
				list($type, $config) = $this->getTypeAndConfig($database);
				if ($type == 'mysql') {
					$this->database = new MysqlDatabase($config, $this->databaseConfigName);
				}
				else {
					trigger_error("Unsupported database: $this->databaseConfigName, type: $type", E_USER_ERROR);
				}
				$DATABASES[$this->databaseConfigName] = $this->database;
			}
			else {
				$this->database = $database;
			}
		}
		return $this->database;
	}

	/**
	 * Connect to the cache if a connection has not already been made.
	 */
	public function cacheConnect() {
		global $CACHES;
		if (!$this->cache && $this->cacheConfigName) {
			$cache = $CACHES[$this->cacheConfigName];
			if (is_string($cache)) {
				list($type, $config) = $this->getTypeAndConfig($cache);
				if ($type == 'memcache') {
					$this->cache = new MemcacheCache($config, $this->cacheConfigName);
				}
				elseif ($type == 'file') {
					$this->cache = new FileCache($config, $this->cacheConfigName);
				}
				else {
					trigger_error("Unsupported cache: $this->cacheConfigName, type: $type", E_USER_ERROR);
				}
				$CACHES[$this->cacheConfigName] = $this->cache;
			}
			else {
				$this->cache = $cache;
			}
		}
		return $this->cache;
	}

	/**
	 * Begin a database transaction.
	 */
	public function beginTransaction() {
		$this->databaseConnect();
		$this->database->beginTransaction();
	}

	/**
	 * Roll back the current database transaction.
	 */
	public function rollbackTransaction() {
		$this->databaseConnect();
		$this->database->commitTransaction();
	}

	/**
	 * Commit the current database transaction.
	 */
	public function commitTransaction() {
		$this->databaseConnect();
		$this->database->commitTransaction();
	}

	/**
	 * Execute some SQL in the database.
	 */
	public function execute($sql) {
		$this->databaseConnect();
		$this->database->query($sql);
	}

	/**
	 * Insert a record into a table, given an associative array of values.
	 */
	public function insert($table, $columnValues) {
		$this->databaseConnect();
		$sql = $this->database->getInsertSql($table, $columnValues);
		$this->execute($sql);
	}

	/**
	 * Update a record in a table, given an associative array of values and a record id.
	 */
	public function update($table, $columnValues, $id) {
		$this->databaseConnect();
		$sql = $this->database->getUpdateSql($table, $columnValues, $id);
		$this->execute($sql);
	}

	/**
	 * Delete a record from a table, given a record id.
	 */
	public function delete($table, $id) {
		$this->databaseConnect();
		$sql = $this->database->getDeleteSql($table, $id);
		$this->execute($sql);
	}

	/**
	 * Query the cache and/or database, and return results as an array of associative arrays.
	 */
	public function results($sql, $cacheTimeInSeconds = false) {
		// Try getting cached results.
		if ($cacheTimeInSeconds !== false && $this->cacheConnect()) {
			$cacheKey = strlen($sql) < 255 ? $sql : md5($sql);
			$value = $this->cache->get($cacheKey);
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
			$this->cache->set($cacheKey, serialize($results), $cacheTimeInSeconds);
		}
		return $results;
	}

	/**
	 * Query the cache and/or database for one row, and return an associative array.
	 */
	public function result($sql, $cacheTimeInSeconds = false) {
		$results = $this->results($sql, $cacheTimeInSeconds);
		return count($results) ? $results[0] : NULL;
	}

}
