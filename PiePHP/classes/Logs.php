<?php
/**
 * Write to log files in the application's logs directory.
 * If this is a production, we'll only write to the error log.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class Logs {

	/**
	 * Log a debug message, as long as we're not in a production environment.
	 * @param  $message: the message to be logged.
	 */
	public static function debug($message) {
		global $ENVIRONMENT;
		if ($ENVIRONMENT != 'production') {
			Logs::appendLogMessage('all.log', 'DEBUG ' . $message);
			Logs::appendLogMessage('debug.log', $message);
		}
	}

	/**
	 * Log an error message.
	 * @param  $message: the message to be logged.
	 */
	public static function error($message) {
		global $ENVIRONMENT;
		if ($ENVIRONMENT != 'production') {
			Logs::appendLogMessage('all.log', 'ERROR ' . $message);
		}
		Logs::appendLogMessage('error.log', $message);
	}

	/**
	 * Append a message to a log file with a given name.
	 * @param  $logName: the name of the file (all/debug/error).
	 * @param  $message: the message to be written to the file.
	 */
	private static function appendLogMessage($logName, $message) {
		global $SITE_DIR;
		FileUtility::appendLine($SITE_DIR . 'logs/' . $logName, $message);
	}

}
