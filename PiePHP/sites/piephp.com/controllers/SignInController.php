<?php
/**
 * The sign in page for PiePHP
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
	public function indexAction() {
		if (count($_POST)) {
			$this->processSignIn();
		}
		$this->renderView('sign_in/sign_in', array('title' => 'Sign In'));
	}

	/**
	 * Process a sign in by looking the user up in the database and comparing password hashes.
	 */
	public function processSignIn() {
		$username = $_POST['username'];
		$password = $_POST['password'];
		$this->loadModel();
		$result = $this->model->result("
			SELECT id, password
			FROM users
			WHERE LOWER(username) = '" . addslashes($username) . "'");
		if ($result) {
			$hash = md5($password . $result['id']);
			if ($result['password'] == $hash) {
				$this->signInSucceeded($result['id'], $username);
			}
			else {
				$this->signInFailed();
			}
		}
		else {
			$this->signInFailed();
		}
	}

	/**
	 * The sign in succeeded, so start a session.
	 * @param  $userId: the ID of the user who signed in.
	 * @param  $username: the username of the user who signed in.
	 */
	public function signInSucceeded($userId, $username) {
		$keepUserSignedIn = isset($_POST['keepSignedIn']) ? 1 : 0;
		$session = new Session();
		$session->start($userId, $keepUserSignedIn);
		echo "<script>alert('succeeded! user: $userId, remember: $keepUserSignedIn')</script>";
		exit;
	}

	/**
	 * The sign in failed, so show the appropriate message.
	 */
	public function signInFailed() {
		echo "<script>alert('failed')</script>";
		exit;
	}
}
