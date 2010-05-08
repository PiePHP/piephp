<?

// Include the local configuration.
require 'configuration(local).php';
require 'configuration(' . ENVIRONMENT . ').php';

// If the calling page has not overridden them, set the array of default required files.
if (!isset($REQUIRED_FILES)) {
	$REQUIRED_FILES = array(
		PIE_ROOT . 'libraries/Pie.class.php',
		PIE_ROOT . 'libraries/PieTimer.class.php',
		PIE_ROOT . 'libraries/PieAuthentication.class.php',
		PIE_ROOT . 'libraries/PieCache(memcache).class.php',
		PIE_ROOT . 'libraries/PieDb(mysql).class.php',
		PIE_ROOT . 'libraries/PieLayout.class.php',
		PIE_ROOT . 'libraries/PieLogging.class.php',
		PIE_ROOT . 'libraries/PieRefresher.class.php',
		PIE_ROOT . 'libraries/PieRequests.class.php',
		PIE_ROOT . 'libraries/PieSay.class.php',
		PIE_ROOT . 'libraries/PieScraping.class.php');
}

// Include the required files; break on failure.
while (list(, $REQUIRED_FILE) = each($REQUIRED_FILES)) {
	require $REQUIRED_FILE;
}

// The calling page's file and this file were also required to build the page.
$REQUIRED_FILES[] = $_SERVER['SCRIPT_FILENAME'];
$REQUIRED_FILES[] = APP_ROOT . 'initialization/common.php';
$REQUIRED_FILES[] = APP_ROOT . 'initialization/configuration(local).php';
$REQUIRED_FILES[] = APP_ROOT . 'initialization/configuration(' . ENVIRONMENT . ').php';

?>