<?php

$DATABASES['default'] = 'mysql:host=localhost username=root password=am12am12 database=piephp';

$URL_ROOT = '/index.php/';

$REFRESHER_FILE = 'C:/Program Files/Macromedia/HomeSite 5/AutoBackup/AutoBackup.ini';

$ENVIRONMENT = 'development';
$PACKAGE = 'PiePHP';
$COPYRIGHT = 'Copyright (c) 2007-2010, Pie Software Foundation';
$AUTHOR = 'Sam Eubank <sam@piephp.com>';
$LICENSE = 'http://www.piephp.com/license';

$SALT = 'OMGWTFBBQ';


/**
 * Print a value to the page with formatting preserved.
 * @param  $var: the value to print.
 */
function p($var) {
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

/**
 * Print a value to the page with formatting preserved, then exit.
 * @param  $var: the value to print.
 */
function x($var) {
	p($var);
	exit;
}
