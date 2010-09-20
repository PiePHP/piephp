<?php
/**
 * Database wrapper class for multiple RDBMS implementations.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2007-2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

abstract class Database {

	/**
	 * Persistent database connection.
	 */
	public $connection;

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
		if ($sql) {
			$message .= '<br><kbd>' . $sql . '</kbd>';
		}
		trigger_error($message, E_USER_ERROR);
	}

}
