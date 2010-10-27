<?php
/**
 * Database wrapper for a MySQL database.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class MysqlDatabase extends Database {

	/**
	 * Connect to MySQL, using the configuration parameters provided.
	 * @param  $config: an associative array of configuration parameters.
	 * @param  $configName: the configuration comes from $DATABASES[$configName].
	 */
	public function __construct($config, $configName = 'default') {
		$this->connection = mysql_pconnect($config['host'], $config['username'], $config['password'])
			or $this->triggerError('Could not connect to ' . $configName . ' database.');
		mysql_select_db($config['database'])
			or $this->triggerError('Database ' . $config['database'] . ' does not exist.');
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
	 * @return a MySQL recordset resource.
	 */
	public function query($sql) {
		$sql = trim($sql);
		$resource = mysql_query($sql, $this->connection)
			or $this->triggerError(mysql_error(), $sql);
		if (strpos($sql, 'INSERT') === 0) {
			$this->insertId = mysql_insert_id($this->connection);
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
			$fetcher = $valuesOnly ? 'mysql_fetch_row' : 'mysql_fetch_assoc';
			while ($assoc = $fetcher($resource)) {
				$results[] = $assoc;
			}
		}
		return $results;
	}

}
