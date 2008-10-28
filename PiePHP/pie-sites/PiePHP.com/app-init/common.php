<?php

// Include the local configuration.
require 'configuration(local).php';
require 'configuration('.ENVIRONMENT.').php';

// If the calling page has not overridden them, set the array of default required files.
if (!isset($REQUIRED_FILES)) {
	$REQUIRED_FILES = array(
		PIE_ROOT.'pie-libraries/PieAuthentication.class.php',
		PIE_ROOT.'pie-libraries/PieRefresher.class.php',
		PIE_ROOT.'pie-libraries/PieCaching.class.php',
		PIE_ROOT.'pie-libraries/PieDatabase-mysql.class.php',
		PIE_ROOT.'pie-libraries/PieLayout.class.php',
		PIE_ROOT.'pie-libraries/PieLogging.class.php',
		PIE_ROOT.'pie-libraries/PieRequests.class.php',
		PIE_ROOT.'pie-libraries/PieSay.class.php',
		PIE_ROOT.'pie-libraries/PieScraping.class.php',
		PIE_ROOT.'pie-libraries/PieTimer.class.php');
}

// Include the required files, break on failure.
while (list(, $REQUIRED_FILE) = each($REQUIRED_FILES)) {
	require $REQUIRED_FILE;
}

// The calling page's file and this file were also required to build the page.
$REQUIRED_FILES[] = $_SERVER['SCRIPT_FILENAME'];
$REQUIRED_FILES[] = APP_ROOT.'app-init/common.php';
$REQUIRED_FILES[] = APP_ROOT.'app-init/configuration(local).php';
$REQUIRED_FILES[] = APP_ROOT.'app-init/configuration('.ENVIRONMENT.').php';

?>