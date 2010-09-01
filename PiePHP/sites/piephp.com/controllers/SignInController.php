<?php

class SignInController extends Controller {

	function indexAction() {
		if (count($_POST)) {
			$this->processSignIn();
		}
		$this->renderView('sign_in/sign_in', array('title' => 'Sign In'));
	}
	
	function processSignIn() {
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
	
	function signInSucceeded($userId, $username) {
		$session = new Session();
		$session->start($userId);
		echo "<script>alert('succeeded')</script>";
		exit;
	}
	
	function signInFailed() {
		echo "<script>alert('failed')</script>";
		exit;
	}
}
