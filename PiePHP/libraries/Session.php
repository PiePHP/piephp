<?php
/**
 * Manage a session that uses a hash, a user ID and a time to authenticate users.
 * Session data can be stored via models, and location-specific data can be stored with cookies.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class Session {

	/**
	 * The name of the cookie that is used to store a PiePHP session.
	 */
	public $sessionCookieKey = 'ps';

	/**
	 * The number of days to keep a user signed in if they have checked the "Keep me signed in" checkbox.
	 */
	public $keepSignedInDurationInDays = 14;

	/**
	 * The number of days to keep remember the user who
	 */
	public $rememberUserIdDurationInDays = 3650;

	/**
	 * Start a session.
	 * @param  $userId: the ID of the user we are starting a session for.
	 * @param  $keepUserSignedIn: whether the user has selected the option to stay signed in.
	 */
	public function start($userId, $keepUserSignedIn = false) {
		$userHash = md5($userId . time() . $GLOBALS['SALT']);
		$cookieValue = $userHash . '-' . $userId . '-' . time();

		if ($keepUserSignedIn) {
			// Remember who the user is for a very long time.
			$expire = time() + 86400 * $this->rememberUserIdDurationInDays;
			setcookie($this->sessionCookieKey, $cookieValue, 0);
		}
		else {
			// Use a session cookie.
			$expire = 0;
		}
		setcookie($this->sessionCookieKey, $cookieValue, $expire);
	}

	/**
	 * Authenticate a user.
	 */
	public function authenticate() {
	}

}