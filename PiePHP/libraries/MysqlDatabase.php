<?php
/**
 * Database wrapper for a MySQL database.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2007-2010, Pie Software Foundation
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
	 * @return an array of associative arrays.
	 */
	public function results($sql) {
		$resource = $this->query($sql);
		$results = array();
		if ($resource) {
			while ($assoc = mysql_fetch_assoc($resource)) {
				$results[] = $assoc;
			}
		}
		return $results;
	}

	/**
	 * Get a SQL statement for inserting a record into a table.
	 * @param  $table: the name of the table we wish to insert into.
	 * @param  $columnValues: an associative array of column values.
	 * @return a string containing a SQL insert statement.
	 */
	public function getInsertSql($table, $columnValues) {
		$columns = array_keys($columnValues);
		$values = array_values($columnValues);
		for ($i = 0; $i < count($values); $i++) {
			$values[$i] = addslashes($values[$i]);
		}
		$sql = 'INSERT INTO ' . $table
			. '(' . join(',', $columns) . ')'
			. " VALUES('" . join("', '", $values) . "')";
		return $sql;
	}

	/**
	 * Get a SQL statement for updating a record in a table.
	 * @param  $table: the name of the table we wish to update.
	 * @param  $columnValues: an associative array of column values.
	 * @param  $id: the ID of the record we wish to update.
	 * @return a string containing a SQL update statement.
	 */
	public function getUpdateSql($table, $columnValues, $id) {
		$sets = array();
		foreach ($columnValues as $column => $value) {
			$sets[] = $column . "='" . addslashes($value) . "'";
		}
		$sql = 'UPDATE ' . $table
			. ' SET ' . join(', ', $sets)
			. ' WHERE id = ' . $id;
		return $sql;
	}

	/**
	 * Get a SQL statement for deleting a record from a table.
	 * @param  $table: the name of the table we wish to delete from.
	 * @param  $id: the ID of the record we wish to delete.
	 * @return a string containing a SQL delete statement.
	 */
	public function getDeleteSql($table, $id) {
		$sql = 'DELETE FROM ' . $table
			. ' WHERE id = ' . $id;
		return $sql;
	}

}
