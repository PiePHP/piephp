<?php
/**
 * Database wrapper for a PostgreSQL database.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class PgDatabase extends Database {

	/**
	 * Connect to PostgreSQL, using the configuration parameters provided.
	 * @param  $config: an associative array of configuration parameters.
	 * @param  $configName: the configuration comes from $DATABASES[$configName].
	 */
	public function __construct($config, $configName = 'default') {
		$this->connection = pg_pconnect($config)
			or $this->triggerError('Could not connect to ' . $configName . ' database.');
	}

	/**
	 * Begin a database transaction.
	 */
	public function beginTransaction() {
		// TODO: Implement transaction stuff.
		//$this->query('BEGIN TRANSACTION');
	}

	/**
	 * Roll back the current database transaction.
	 */
	public function rollbackTransaction() {
		// TODO: Implement transaction stuff.
		//$this->query('ROLLBACK TRANSACTION');
	}

	/**
	 * Commit the current database transaction.
	 */
	public function commitTransaction() {
		// TODO: Implement transaction stuff.
		//$this->query('COMMIT TRANSACTION');
	}

	/**
	 * Do a query on the database, and return a recordset resource.
	 * @param  $sql: a SQL query string.
	 * @return a PostgreSQL recordset resource.
	 */
	public function query($sql) {
		$sql = trim($sql);
		$resource = pg_query($this->connection, $sql)
			or $this->triggerError(pg_error(), $sql);
		if (strpos($sql, 'INSERT') === 0) {
			$this->insertId = pg_insert_id($this->connection);
		}
		return $resource;
	}

	/**
	 * Do a query on the database, and return results.
	 * @param  $sql: a SQL query string.
	 * @param  $valuesOnly: whether to return values only, or return column names as well.
	 * @return results as an array of associative arrays (or an array of arrays if $valuesOnly == true).
	 */
	public function select($sql, $valuesOnly = false) {
		$resource = $this->query('SELECT ' . $sql);
		$results = array();
		if ($resource) {
			$fetcher = $valuesOnly ? 'pg_fetch_row' : 'pg_fetch_assoc';
			while ($assoc = $fetcher($resource)) {
				$results[] = $assoc;
			}
		}
		return $results;
	}

}
