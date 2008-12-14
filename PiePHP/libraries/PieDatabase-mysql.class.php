<?

mysql_connect(DB_HOST, DB_USER, DB_PASS);
mysql_select_db(DB_NAME);

PieDatabase::patch();


class PieDatabase {
	
	static function patch() {
		global $MEMCACHE;
		$patched = false;
		if (ENABLE_REBUILD && isset($_REQUEST['REBUILD'])) {
			//@unlink( APP_ROOT.'webpages/logs/all.log');
			//@unlink( APP_ROOT.'webpages/logs/debug.log');
			//@unlink( APP_ROOT.'webpages/logs/error.log');
			$patched = true;
			//echo 'Dropping tables...<br/>';
			$tables = query('SHOW TABLES');
			while (list($table) = PieDatabase::row($tables)) {
				PieDatabase::query('DROP TABLE '.$table);
			}
			$MEMCACHE->flush();
		}
		$patchQuery = PieDatabase::tryQuery('SELECT Number FROM Patches', &$patchNumberErrorMessage);
		$patchNumber = $patchQuery ? array_shift(PieDatabase::row($patchQuery)) : -1;
		$tryPatch = $patchNumber + 1;
		while (file_exists($path = APP_ROOT.'webpages//_/sql/patch'.sprintf('%04d', $tryPatch).'.sql')) {
			$patched = true;
			//echo 'Running '.$path.'...<br/>';
			$statements = preg_split('/;[\r\n]/', file_get_contents($path));
			for ($i = 0; $i < count($statements); $i++) {
				PieDatabase::tryQuery($statements[$i], &$errorMessage);
				if ($errorMessage && $errorMessage != 'Query was empty') {
					die('<p><b>' . $errorMessage . '</b><br/>' . $statements[$i] . '</p>');
				}
			}
			$tryPatch++;
			PieDatabase::query('UPDATE Patches SET Number = '.$tryPatch);
		}
	}
	
	
	static function field($sql) {
		$result = PieDatabase::select($sql . (preg_match('/ LIMIT ([0-9]+), 1/', $sql) ? '' : ' LIMIT 0, 1'));
		$field = ($result && mysql_num_rows($result)) ? array_shift(mysql_fetch_row($result)) : '';
		mysql_free_result($result);
		return $field;
	}
	
	static function fieldArray($sql) {
		$result = PieDatabase::select($sql);
		$array = array();
		while (list($value) = mysql_fetch_row($result)) {
			$array[] = $value;
		}
		mysql_free_result($result);
		return $array;
	}
	
	static function fieldsArray($sql) {
		$result = PieDatabase::select($sql);
		$array = array();
		while (list($key, $value) = mysql_fetch_row($result)) {
			$array[$key] = $value;
		}
		mysql_free_result($result);
		return $array;
	}
	
	static function row($query) {
		if (is_string($query)) {
			$query = PieDatabase::select($query);
			$row = mysql_fetch_row($query);
			mysql_free_result($query);
			return $row;
		}
		return mysql_fetch_row($query);
	}
	
	static function assoc($query) {
		if (is_string($query)) {
			$query = PieDatabase::select($query);
			$assoc = mysql_fetch_assoc($query);
			mysql_free_result($query);
			return $assoc;
		}
		return mysql_fetch_assoc($query);
	}
	
	static function finish() {
		global $DB_BUFFER;
		ob_end_flush();
		while (list(, $sql) = each($DB_BUFFER)) {
			PieDatabase::query($sql);
		}
		exit;
	}
	
	static function select($sql) {
		return PieDatabase::query('SELECT '.$sql);
	}
	
	static function update($sql) {
		PieDatabase::query('UPDATE '.$sql);
		return mysql_affected_rows();
	}
	
	static function insert($sql) {
		PieDatabase::query('INSERT INTO '.$sql);
		return mysql_insert_id();
	}
	
	static function replace($sql) {
		PieDatabase::query('REPLACE INTO '.$sql);
		return mysql_affected_rows();
	}
	
	static function rows($result) {
		return mysql_num_rows($result);
	}
	
	static function id($result) {
		return mysql_insert_id();
	}
	
	static function query($sql) {
		$result = mysql_query($sql) or die('<p><b>' . mysql_error() . '</b><br/>' . $sql . '</p>');
		return $result;
	}
	
	static function tryQuery($sql, &$errorMessage) {
		@$result = mysql_query($sql) or $errorMessage = mysql_error();
		return $result;
	}
	
	static function makeTime($dateTime) {
		$a = preg_split('/[^0-9]+/', $dateTime);
		return mktime($a[3] * 1, $a[4] * 1, $a[5] * 1, $a[1] * 1, $a[2] * 1, $a[0] * 1);
	}
	
	static function timeString($time = 0) {
		return date("Y-m-d G:i:s", $time ? $time : time());
	}
	
	static function quoteTime($time = 0) {
		return date("'Y-m-d G:i:s'", $time ? $time : time());
	}
	
	static function quote($value) {
		$value .= '';
		return is_numeric($value) ? $value : ($value . '' ? "'" . trim(str_replace("'", "\'", str_replace('\\', '\\\\', $value))) . "'" : 'NULL');
	}
	
}
?>