<?php

// Include the server-specific configuration if it exists.
@include 'configuration.php';

// If no server-specific configuration exists, use the default.
if (!defined('PIE')) require 'configuration-default.php';

// If the calling page has not overridden them, set the array of default required files.
if (!isset($REQUIRED_FILES)) {
	$REQUIRED_FILES = array(
		PIE.'/_/php/libraries/PieAuthentication.class.php',
		PIE.'/_/php/libraries/PieRefresher.class.php',
		PIE.'/_/php/libraries/PieCaching.class.php',
		PIE.'/_/php/libraries/PieDatabase-mysql.class.php',
		PIE.'/_/php/libraries/PieLayout.class.php',
		PIE.'/_/php/libraries/PieLogging.class.php',
		PIE.'/_/php/libraries/PieRequests.class.php',
		PIE.'/_/php/libraries/PieScraping.class.php',
		PIE.'/_/php/libraries/PieTimer.class.php');
}

// Include the required files, breaking on failure.
while (list(, $REQUIRED_FILE) = each($REQUIRED_FILES)) {
	require $REQUIRED_FILE;
}

// The calling page's file and this file were also required to build the page.
$REQUIRED_FILES[] = $_SERVER['SCRIPT_FILENAME'];
$REQUIRED_FILES[] = DOCS.'/common.php';

?>