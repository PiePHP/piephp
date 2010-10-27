<?php
/**
 * Database wrapper class for multiple RDBMS implementations.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

abstract class Database {

	/**
	 * Persistent database connection.
	 */
	public $connection;

	/**
	 * In some situations, we just want SQL errors to fail silently.
	 */
	public $ignoreErrors = false;

	/**
	 * Last ID to be inserted into a table in the database.
	 */
	public $insertId;

	/**
	 * Trigger an error to be handled by the ErrorsController.
	 * @param  $message: a message describing the error.
	 * @param  $sql: the SQL query that resulted in an error.
	 */
	public function triggerError($message, $sql = '') {
		if ($this->ignoreErrors) {
			return;
		}
		if ($sql) {
			$message .= '<br><kbd>' . $sql . '</kbd>';
		}
		trigger_error($message, E_USER_ERROR);
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
