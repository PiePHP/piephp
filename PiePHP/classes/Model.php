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
	 * @param $databaseConfigName: can override the default database for the model.
	 * @return the database connection.
	 */
	public function loadDatabase($databaseConfigName = NULL) {
		global $DATABASES;
		if ($databaseConfigName) {
			$this->databaseConfigName = $databaseConfigName;
		}
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
	 * @param $cacheConfigName: can override the default cache for the model.
	 * @return the cache connection.
	 */
	public function loadCache($cacheConfigName = NULL) {
		global $CACHES;
		if ($cacheConfigName) {
			$this->cacheConfigName = $cacheConfigName;
		}
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
		$this->loadDatabase();
		$this->database->beginTransaction();
	}

	/**
	 * Roll back the current database transaction.
	 */
	public function rollbackTransaction() {
		$this->loadDatabase();
		$this->database->commitTransaction();
	}

	/**
	 * Commit the current database transaction.
	 */
	public function commitTransaction() {
		$this->loadDatabase();
		$this->database->commitTransaction();
	}

	/**
	 * Execute some SQL in the database.
	 * @param  $sql: the SQL query to execute.
	 */
	public function execute($sql) {
		$this->loadDatabase();
		$this->database->query($sql);
	}

	/**
	 * Insert a record into a database table.
	 * @param  $table: the name of the table we wish to insert into.
	 * @param  $columnValues: an associative array of column values.
	 */
	public function insert($table, $columnValues) {
		$this->loadDatabase();
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
		$this->loadDatabase();
		$sql = $this->database->getUpdateSql($table, $columnValues, $id);
		$this->execute($sql);
	}

	/**
	 * Delete a record from a database table.
	 * @param  $table: the name of the table we wish to delete from.
	 * @param  $id: the ID of the record we wish to delete.
	 */
	public function delete($table, $id) {
		$this->loadDatabase();
		$sql = $this->database->getDeleteSql($table, $id);
		$this->execute($sql);
	}

	/**
	 * Query the cache, or failing that, the database, and return results.
	 * @param  $sql: the SQL query to execute.
	 * @param  $cacheTimeInSeconds: the number of seconds to store these database results in the cache.
	 * @return results as an array of associative arrays.
	 */
	public function select($sql, $cacheTimeInSeconds = false) {
		// Try getting cached results.
		if ($cacheTimeInSeconds !== false && $this->loadCache()) {
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
		$this->loadDatabase();
		$results = $this->database->select($sql);
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
	public function selectAssoc($sql, $cacheTimeInSeconds = false) {
		$results = $this->select($sql, $cacheTimeInSeconds);
		return count($results) ? $results[0] : NULL;
	}

	/**
	 * Query the cache, or failing that, the database, and return a single value from a result.
	 * @param  $sql: the SQL query to execute.
	 * @param  $cacheTimeInSeconds: the number of seconds to store this database result in the cache.
	 * @return the result value.
	 */
	public function selectValue($sql, $cacheTimeInSeconds = false) {
		$result = $this->selectAssoc($sql, $cacheTimeInSeconds);
		return count($result) ? $result[0] : NULL;
	}

}
