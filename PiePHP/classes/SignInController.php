<?php
/**
 * The sign in page for PiePHP.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class SignInController extends Controller {

	/**
	 * Process a sign in if data has been posted.
	 * If processing does not redirect us somewhere, then show the sign in page.
	 */
	public function defaultAction() {
		if (count($_POST)) {
			$this->processSignIn();
		}
		// We must pass a title because controllers that require authorization will call it
		// and then exit.  So the dispatcher's output decorating won't be done.
		$this->render(array(
			'title' => 'Sign in'
		));
	}

	/**
	 * Process a sign in by looking the user up in the database and comparing password hashes.
	 */
	public function processSignIn() {
		$username = $_POST['username'];
		$password = $_POST['password'];
		$this->loadModel();
		$result = $this->model->selectAssoc("
			id, username, password, user_groups
			FROM users
			WHERE LOWER(username) = '" . addslashes($username) . "'");
		if ($result) {
			$hash = md5($password . $result['id']);
			if ($result['password'] == $hash) {
				$this->startSessionAndSendRedirect($result['id'], $result['username'], $result['user_groups']);
			}
		}
	}

	/**
	 * The sign in succeeded, so start a session and send the user to the appropriate page.
	 * @param  $userId: the ID of the user who signed in.
	 * @param  $username: the username of the user who signed in.
	 * @param  $userGroups: a string containing IDs of user groups that the user belongs to.
	 */
	public function startSessionAndSendRedirect($userId, $username, $userGroups) {
		$keepUserSignedIn = isset($_POST['keepSignedIn']) ? 1 : 0;
		$session = new Session();
		$session->start($userId, $username, $userGroups, $keepUserSignedIn);
		if (isset($_COOKIE['SIGN_IN_REDIRECT_URL'])) {
			$this->sendJsRedirect($_COOKIE['SIGN_IN_REDIRECT_URL']);
		}
		else {
			$this->sendJsRedirect($GLOBALS['HTTP_ROOT']);
		}
	}

}
