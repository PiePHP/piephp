<?php

class Database {

	public $connection;

	public $insertId;

	/**
	 * Show or log an error.
	 */
	function triggerError($message, $sql = '') {
		if ($sql) {
			$message .= '<br><kbd>' . $sql . '</kbd>';
		}
		trigger_error($message, E_USER_ERROR);
	}

}
