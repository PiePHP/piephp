<?php

class MysqlDatabase extends Database {

	/**
	 * Connect to MySQL, using the configuration parameters provided.
	 */
	function __construct($config, $configKey = 'default') {
		$this->connection = mysql_pconnect($config['host'], $config['username'], $config['password'])
			or $this->error('Could not connect to ' . $configKey . ' database.');
		mysql_select_db($config['database']);
	}

	/**
	 * Do a query with the SQL provided, and return a recordset resource.
	 */
	function query($sql) {
		$resource = mysql_query($sql, $this->connection) or $this->error(mysql_error());
		return $resource;
	}

	/**
	 * Do a query with the SQL provided, and return an array of associative arrays.
	 */
	function results($sql) {
		$resource = $this->query($sql);
		$results = array();
		while ($assoc = mysql_fetch_assoc($resource)) {
			$results[] = $assoc;
		}
		return $results;
	}

}
