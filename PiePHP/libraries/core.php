<?

$REQUIRED_FILES = array(
	'/_/php/custom-libraries/data-structure.php',
	'/_/php/libraries/authentication.php',
	'/_/php/libraries/refresher.php',
	'/_/php/libraries/caching.php',
	'/_/php/libraries/database-mysql.php',
	'/_/php/libraries/layout.php',
	'/_/php/libraries/logging.php',
	'/_/php/libraries/requests.php',
	'/_/php/libraries/scraping.php',
	'/_/php/libraries/timer.php');

while (list(, $REQUIRED_FILE) = each($REQUIRED_FILES)) {
	require WEB_DOCUMENTS.$REQUIRED_FILE;
}

$REQUIRED_FILES[] = $_SERVER['SCRIPT_NAME'];
$REQUIRED_FILES[] = '/_/php/core.php';


$DB_BUFFER = array();

PatchDatabase();

ob_start();


?>