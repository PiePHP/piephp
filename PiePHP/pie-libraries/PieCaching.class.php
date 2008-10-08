<?php

$GLOBALS['MEMCACHE'] = memcache_connect(MEMCACHE_HOST, MEMCACHE_PORT);


class PieCaching {
	
	/**
	 * Get a record from memcache, or get it from the database if it's not in memcache.
	 *
	 * $table - The database table where the record may be found.
	 *	 e.g.	'Authors'
	 *
	 * $values - An associative array of key columns and values.
	 *	 e.g.	array('Id' => 1, 'Name' => 'Sam Eubank')
	 *
	 */
	static function fetchRow($table, $values) {
		global $MEMCACHE, $DATA_STRUCTURE;
		
		// Allow the values to just be an Id.
		if (is_numeric($values)) {
			$values = array('Id' => $values);
		}
		
		// Retrieve the structure of the table which is stored in a global array.
		$fields = $DATA_STRUCTURE[$table];
		
		if ($MEMCACHE) {
			// Iterate through all of the keys by which this record needs to be stored in memcache.
			for ($key = 0; isset($fields[$key]); $key++) {
				$keyFields = array();
				while (list(, $field) = each($fields[$key])) {
					if (!isset($values[$field])) continue 2;
					$keyFields[] = $field.':'.str_replace(',', '&comma;', $values[$field]);
				}
				if ($storedValues = $MEMCACHE->get($table.'('.join(',', $keyFields).')')) {
					return unserialize($storedValues);
				}
			}
		}
		
		// The record was not in memcache, so get it from the database.
		for ($key = 0; isset($fields[$key]); $key++) {
			$keyFields = $fields[$key];
			$where = array();
			while (list(, $field) = each($fields[$key])) {
				if (!isset($values[$field])) continue 2;
				$where[] = $field.'='.Quote($values[$field]);
			}
			$result = Select("* FROM $table WHERE ".join(' AND ', $where));
			if (mysql_num_rows($result) == 1) {
				return Assoc($result);
			}
		}
		
		return false;
	}
	
	
	/**
	 * Insert or update a record in the specified table, and update any stored instances in memcache.
	 *
	 * $table - The database table the record should be replaced into.
	 *	 e.g.	'Authors'
	 *
	 * $values - An associative array of database columns and values for the new or updated record.
	 *	 e.g.	array('Id' => 1, 'Name' => 'Sam Eubank')
	 *
	 */
	static function storeRow($table, $values) {
		global $MEMCACHE, $DATA_STRUCTURE;
		
		// If the record already exists, get any existing data that is not being replaced.
		if ($storedValues = FetchRow($table, $values)) {
			while (list($column, $value) = each($storedValues)) {
				if (!isset($values[$column])) {
					$values[$column] = $value;
				}
			}
		}
		
		// Iterate through all of the fields that need to be set so they can be joined in a replace statement.
		$sets = array();
		while (list($column, $value) = each($values)) {
			$sets[] = $column.'='.Quote($value);
		}
		
		// Replace the record, knowing that if it does not exist, it will be created.
		$result = Query('REPLACE INTO '.$table.' SET '.join(',', $sets));
		
		// If an Id was auto-generated, store it so that it can be cached.
		if ($id = mysql_insert_id()) {
			$values['Id'] = $id;
		}
		
		if ($MEMCACHE) {
			// Retrieve the structure of the table which is stored in a global array.
			$fields = $DATA_STRUCTURE[$table];
			
			// Iterate through all of the keys by which this record needs to be stored in memcache.
			for ($key = 0; isset($fields[$key]); $key++) {
				$keyFields = array();
				while (list(, $field) = each($fields[$key])) {
					$keyFields[] = $field.':'.str_replace(',', '&comma;', $values[$field]);
				}
				$MEMCACHE->set($table.'('.join(',', $keyFields).')', serialize($values));
			}
		}
		
		return $values;
	}
	
}
?>