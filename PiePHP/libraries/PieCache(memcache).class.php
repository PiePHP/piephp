<?

if (CACHE_ENABLED) {
	$GLOBALS['CACHE'] = memcache_connect(CACHE_HOST, CACHE_PORT);
	if (CACHE_TRACKING) {
		$GLOBALS['CACHE_REQUESTS'] = array();
	}
}

define('CACHE_ONLY', 0);
define('DB_ONLY', 1);
define('CACHE_AND_DB', 2);

define('FETCH_ONLY', 0);
define('STORE_ONLY', 1);
define('FETCH_AND_STORE', 2);


class PieCache {
	
	/**
	 * Get an associative array representing the structure of a table.
	 */
	static function get($key) {
		global $CACHE;
		if (CACHE_TRACKING) {
			PieTimer::start('CACHE_TRACKING');
		}
		$value = $CACHE->get(CACHE_PREFIX.$key);
		if (CACHE_TRACKING) {
			$GLOBALS['CACHE_REQUESTS'][] = array('get' => $key, 'value' => $value, 'time' => PieTimer::finish('CACHE_TRACKING'));
		}
		return $value;
	}
	
	/**
	 * Get an associative array representing the structure of a table.
	 */
	static function set($key, $value, $expirationSeconds = 0) {
		global $CACHE;
		if (CACHE_TRACKING) {
			PieTimer::start('CACHE_TRACKING');
		}
		$CACHE->set(CACHE_PREFIX.$key, $value, 0, $expirationSeconds);
		if (CACHE_TRACKING) {
			$GLOBALS['CACHE_REQUESTS'][] = array('set' => $key, 'value' => $value, 'time' => PieTimer::finish('CACHE_TRACKING'));
		}
		return $value;
	}
	
	/**
	 * Get an associative array representing the structure of a table.
	 */
	static function fetchResults($sql, $firstColumnIsId = false, $engine = CACHE_AND_DB, $mode = FETCH_AND_STORE, $expirationSeconds = 0, $customKey = '') {
		global $CACHE;
		$key = $customKey ? $customKey : md5($sql);
		if ($engine != DB_ONLY && $mode != STORE_ONLY) {
			$results = unserialize(PieCache::get($key));
			if ($results) {
				return $results;
			}
		}
		if ($engine != CACHE_ONLY) {
			$query = PieDb::query($sql);
			if (PieDb::rows($query)) {
				$results = array();
				for ($rowIndex = 0; $row = PieDb::row($query); $rowIndex++) {
					if ($firstColumnIsId) {
						$id = array_unshift($row);
						$results[$id] = $row;
					}
					else {
						$results[$rowIndex] = $row;
					}
				}
				if ($mode != FETCH_ONLY) {
					PieCache::set($key, serialize($results));
				}
				return $results;
			}
		}
		return false;
	}
	
	/**
	 * Get an associative array representing the structure of a table.
	 */
	static function getTableStructure($table) {
		global $SCHEMA;
		
		if (!isset($SCHEMA[$table])) {
			require APP_ROOT . 'schema/' . $table . '.php';
		}
		return $SCHEMA[$table];
	}
	
	/**
	 * Get a record from memcache, or get it from the database if it's not in memcache.
	 *
	 * $table - The database table where the record may be found.
	 *	 e.g.	'authors'
	 *
	 * $values - An associative array of key columns and values.
	 *	 e.g.	array('id' => 1, 'name' => 'Sam Eubank')
	 *
	 */
	static function fetchRow($table, $values, $engine = CACHE_AND_DB, $mode = FETCH_AND_STORE) {
		global $CACHE;
    
		// Allow the values to just be an id.
		if (is_numeric($values)) {
			$values = array('id' => $values);
		}
		
		// Retrieve the structure of the table which is stored in a global array.
		$fields = PieCache::getTableStructure($table);
		
		if ($CACHE && $engine != DB_ONLY) {
			// Iterate through all of the keys by which this record needs to be stored in memcache.
			for ($key = 0; isset($fields[$key]); $key++) {
        $keyFields = array();
				while (list(, $field) = each($fields[$key])) {
					if (!isset($values[$field])) continue 2;
					$keyFields[] = $field . ':' . str_replace(',', '&comma;', $values[$field]);
				}
				if ($storedValues = PieCache::get($table . '(' . join(',', $keyFields) . ')')) {
					return unserialize($storedValues);
				}
			}
		}
		
		if ($engine != CACHE_ONLY) {
			// The record was not in memcache, so get it from the database.
			for ($key = 0; isset($fields[$key]); $key++) {
				$keyFields = $fields[$key];
				$where = array();
				while (list(, $field) = each($fields[$key])) {
					if (!isset($values[$field])) continue 2;
					$where[] = $field . '=' . PieDb::quote($values[$field]);
				}
				$result = PieDb::select("* FROM $table WHERE " . join(' AND ', $where));
				if (PieDb::rows($result) == 1) {
					$assoc = PieDb::assoc($result);
					if ($mode != FETCH_ONLY) {
						PieCache::storeRow($table, $assoc, $engine, STORE_ONLY);
					}
					return $assoc;
				}
			}
		}
		
		return false;
	}
	
	
	/**
	 * Insert or update a record in the specified table, and update any stored instances in memcache.
	 *
	 * $table - The database table the record should be replaced into.
	 *	 e.g.	'authors'
	 *
	 * $values - An associative array of database columns and values for the new or updated record.
	 *	 e.g.	array('id' => 1, 'name' => 'Sam Eubank')
	 *
	 */
	static function storeRow($table, $values, $engine = CACHE_AND_DB, $mode = FETCH_AND_STORE) {
		global $CACHE;
		
		$GLOBALS['caches']++;
		
		if ($mode != STORE_ONLY) {
			// If the record already exists, get any existing data that is not being replaced.
			if ($storedValues = PieCache::fetchRow($table, $values)) {
				while (list($column, $value) = each($storedValues)) {
					if (!isset($values[$column])) {
						$values[$column] = $value;
					}
				}
			}
		}
		
		if ($engine != CACHE_ONLY) {
			
			$GLOBALS['stores']++;
			
			// Iterate through all of the fields that need to be set so they can be joined in a replace statement.
			$sets = array();
			while (list($column, $value) = each($values)) {
				$sets[] = $column . '=' . PieDb::quote($value);
			}
			
			// Replace the record, knowing that if it does not exist, it will be created.
			$result = PieDb::replace($table . ' SET ' . join(',', $sets));
			
			// If an id was auto-generated, store it so that it can be cached.
			if ($id = PieDb::id($result)) {
				$values['id'] = $id;
			}
		}
		
		if ($CACHE && $engine != DB_ONLY) {
			// Retrieve the structure of the table which is stored in a global array.
			$fields = PieCache::getTableStructure($table);
			
			// Iterate through all of the keys by which this record needs to be stored in memcache.
			for ($key = 0; isset($fields[$key]); $key++) {
				$keyFields = array();
				while (list(, $field) = each($fields[$key])) {
					$keyFields[] = $field . ':' . str_replace(',', '&comma;', $values[$field]);
				}
				PieCache::set($table . '(' . join(',', $keyFields) . ')', serialize($values));
			}
		}
		
		return $values;
	}
	
	/**
	 * Update the relational popularity (AKA buzz) of records that are parents of this record.
	 */
	static function storeBuzz($table, $values, $timeField, $foreignKeys, $permanences, $storageInterval = 1, $storageTimeout = 0) {
	
		while (list($foreignKey, $foreignTable) = each($foreignKeys)) {
			
			$foreignValues = PieCache::fetchRow($foreignTable, array('id' => $values[$foreignKey]));
			
			$count = ++$foreignValues[$table . '_count'];
			$lastTime = PieDb::makeTime($foreignValues[$table . '_counted']);
			$thisTime = PieDb::makeTime($foreignValues[$table . '_counted'] = $values[$timeField]);
			$lull = max(1, $thisTime - $lastTime);
			
			reset($permanences);
			while (list(, $permanence) = each($permanences)) {
				$recency = 1 / min($count, $permanence);
				$average = $foreignValues[$table . '_lull_' . $permanence];
				$average = $lull * $recency + $average * (1 - $recency);
				$foreignValues[$table . '_lull_' . $permanence] = $average;
			}
			
			PieCache::storeRow($foreignTable, $foreignValues, ($count % $storageInterval) == 0 || $lull > $storageTimeout);
		}
		reset($foreignKeys);
	}
	
}
?>