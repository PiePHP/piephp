<?php

class Session {

	public $sessionCookieKey = 'pies';

	public $keepSignedInDurationInDays = 14;

	public $rememberUserIdDurationInDays = 3650;

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

	public function authenticate() {
	}

}