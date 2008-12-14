<?

class PieRequests {
	
	static function redirect($URL) {
		header('Location: '.$URL);
		exit;
	}
	
}
?>