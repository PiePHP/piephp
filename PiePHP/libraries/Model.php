<?php
/**
 * A Model interacts with a Database and/or a Cache.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2007-2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class Model {

	/**
	 * The config name corresponds to a key in the $DATABASES array.
	 */
	public $databaseConfigName = 'default';

	/**
	 * After connecting to the database, we store the connection.
	 */
	public $database = NULL;

	/**
	 * The config name corresponds to a key in the $CACHES array.
	 */
	public $cacheConfigName = 'default';

	/**
	 * After connecting to the cache, we store the connection.
	 */
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
	 * @return the database connection.
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
	 * @return the cache connection.
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
	 * @param  $sql: the SQL query to execute.
	 */
	public function execute($sql) {
		$this->databaseConnect();
		$this->database->query($sql);
	}

	/**
	 * Insert a record into a database table.
	 * @param  $table: the name of the table we wish to insert into.
	 * @param  $columnValues: an associative array of column values.
	 */
	public function insert($table, $columnValues) {
		$this->databaseConnect();
		$sql = $this->database->getInsertSql($table, $columnValues);
		$this->execute($sql);
	}

	/**
	 * Update a record in a database table.
	 * @param  $table: the name of the table we wish to update.
	 * @param  $columnValues: an associative array of column values.
	 * @param  $id: the ID of the record we wish to update.
	 */
	public function update($table, $columnValues, $id) {
		$this->databaseConnect();
		$sql = $this->database->getUpdateSql($table, $columnValues, $id);
		$this->execute($sql);
	}

	/**
	 * Delete a record from a database table.
	 * @param  $table: the name of the table we wish to delete from.
	 * @param  $id: the ID of the record we wish to delete.
	 */
	public function delete($table, $id) {
		$this->databaseConnect();
		$sql = $this->database->getDeleteSql($table, $id);
		$this->execute($sql);
	}

	/**
	 * Query the cache, or failing that, the database, and return results.
	 * @param  $sql: the SQL query to execute.
	 * @param  $cacheTimeInSeconds: the number of seconds to store these database results in the cache.
	 * @return results as an array of associative arrays.
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
	 * Query the cache, or failing that, the database, and return a result.
	 * @param  $sql: the SQL query to execute.
	 * @param  $cacheTimeInSeconds: the number of seconds to store this database result in the cache.
	 * @return the result as an associative array.
	 */
	public function result($sql, $cacheTimeInSeconds = false) {
		$results = $this->results($sql, $cacheTimeInSeconds);
		return count($results) ? $results[0] : NULL;
	}

}
