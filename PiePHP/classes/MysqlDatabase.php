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
		$this->connection = mysql_pconnect($config['host'], $config['user'], $config['password'])
			or $this->triggerError('Could not connect to ' . $configName . ' database.');
		mysql_select_db($config['dbname'])
			or $this->triggerError('Database ' . $config['dbname'] . ' does not exist.');
	}

	/**
	 * Begin a database transaction.
	 */
	public function beginTransaction() {
		$this->query('BEGIN');
	}

	/**
	 * Roll back the current database transaction.
	 */
	public function rollbackTransaction() {
		$this->query('ROLLBACK');
	}

	/**
	 * Commit the current database transaction.
	 */
	public function commitTransaction() {
		$this->query('COMMIT');
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
	 * @param  $returnAssociativeArrays: whether to return each row as an associative array.
	 * @return results as an array of associative arrays (or an array of arrays if $returnAssociativeArrays == false).
	 */
	public function select($sql, $returnAssociativeArrays = true) {
		$resource = $this->query('SELECT ' . $sql);
		$results = array();
		if ($resource) {
			$fetchFunction = $returnAssociativeArrays ? 'mysql_fetch_assoc' : 'mysql_fetch_row';
			while ($assoc = $fetchFunction($resource)) {
				$results[] = $assoc;
			}
		}
		return $results;
	}

}
