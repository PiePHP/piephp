<?php

class Database {

	public $connection;

	/**
	 * Show or log an error.
	 */
	function error($message) {
		if (ini_get('display_errors')) {
			die($message);
		}
		else {
			error_log($sql) and die();
		}
	}

}
