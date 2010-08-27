<?php

class MysqlDatabase extends Database {

	/**
	 * Connect to MySQL, using the configuration parameters provided.
	 */
	function __construct($config, $configName = 'default') {
		$this->connection = mysql_pconnect($config['host'], $config['username'], $config['password'])
			or $this->triggerError('Could not connect to ' . $configName . ' database.');
		mysql_select_db($config['database'])
			or $this->triggerError('Database ' . $config['database'] . ' does not exist.');
	}

	/**
	 * Begin a database transaction.
	 */
	function beginTransaction() {
		//$this->query('BEGIN TRANSACTION');
	}

	/**
	 * Roll back the current database transaction.
	 */
	function rollbackTransaction() {
		//$this->query('ROLLBACK TRANSACTION');
	}

	/**
	 * Commit the current database transaction.
	 */
	function commitTransaction() {
		//$this->query('COMMIT TRANSACTION');
	}

	/**
	 * Do a query with the SQL provided, and return a recordset resource.
	 */
	function query($sql) {
		$sql = trim($sql);
		$resource = mysql_query($sql, $this->connection)
			or $this->triggerError(mysql_error(), $sql);
		if (strpos($sql, 'INSERT') === 0) {
			$this->insertId = mysql_insert_id($this->connection);
		}
		return $resource;
	}

	/**
	 * Do a query with the SQL provided, and return an array of associative arrays.
	 */
	function results($sql) {
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
	 * Get a SQL statement for inserting a record into a table, given an associative array of values.
	 */
	function getInsertSql($table, $columnValues) {
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
	 * Get a SQL statement for updating a record in a table, given an associative array of values and a record id.
	 */
	function getUpdateSql($table, $columnValues, $id) {
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
	 * Get a SQL statement for deleting a record from a table, given a record id.
	 */
	function getDeleteSql($table, $id) {
		$sql = 'DELETE FROM ' . $table
			. ' WHERE id = ' . $id;
		return $sql;
	}

}
