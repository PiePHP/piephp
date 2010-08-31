<?php

class SignInController extends Controller {

	function indexAction() {
		if (count($_POST)) {
			$this->sendRedirect($GLOBALS['HTTP_ROOT']);
		}
		$this->renderView('sign_in/sign_in', array('title' => 'Sign In'));
	}
}
