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
	 * We assume that a user is not signed in until we have checked their credentials to prove otherwise.
	 */
	public $isSignedIn = false;

	/**
	 * When we have authenticated or started a session, controllers and libraries can access the user's ID.
	 */
	public $userId;

	/**
	 * If the user has a session cookie, check its parameters against a privately salted hash to authenticate. 
	 */
	public function __construct() {
		if (isset($_COOKIE[$this->sessionCookieKey])) {
			$cookieValue = $_COOKIE[$this->sessionCookieKey];
			$values = explode('-', $cookieValue, 5);
			if (count($values) == 5) {
				list($userHash, $userId, $time, $userGroups, $username) = $values;
				$expectedHash = $this->makeHash($userId, $time, $userGroups);
				if ($userHash == $expectedHash) {
					$this->isSignedIn = true;
					$this->userId = $userId;
					$this->username = $username;
					$this->userGroups = explode('.', $userGroups);
				}
			}
		}
	}

	/**
	 * Start a session by setting a session cookie with hash, user ID and time.
	 * Also keep track of the username.
	 * @param  $userId: the ID of the user we are starting a session for.
	 * @param  $username: the username, in case we need to display it without hitting the database.
	 * @param  $userGroups: a string containing IDs of user groups that the user belongs to.
	 * @param  $keepUserSignedIn: whether the user has selected the option to stay signed in.
	 */
	public function start($userId, $username, $userGroups, $keepUserSignedIn = false) {
		$time = time();

		// Use a "." as a user groups delimiter because it won't get escaped.
		$userGroups = str_replace(',', '.', $userGroups);

		$hash = $this->makeHash($userId, $time, $userGroups);
		$cookieValue = $hash . '-' . $userId . '-' . $time . '-' . $userGroups . '-' . $username;

		if ($keepUserSignedIn) {
			// Remember who the user is for a very long time.
			$expire = $time + 86400 * $this->rememberUserIdDurationInDays;
		}
		else {
			// Use a session cookie.
			$expire = NULL;
		}
		setcookie($this->sessionCookieKey, $cookieValue, $expire, '/');
	}

	/**
	 * End a session by deleting the session cookie.
	 */
	public function end() {
		setcookie($this->sessionCookieKey, '', NULL, '/');
	}

	/**
	 * Hash a user ID with a private salt in order to start or authenticate a session.
	 * @param  $userId: the ID of the user we are starting a session for.
	 * @param  $time: the time of session creation.
	 * @param  $userGroups: a string containing IDs of user groups that the user belongs to.
	 * @return the md5 hash that the cookie uses to verify the authenticity of cookie values.
	 */
	public function makeHash($userId, $time, $userGroups) {
		$hash = md5($userId . $time . $userGroups . $GLOBALS['SALT']);
		return $hash;
	}

}