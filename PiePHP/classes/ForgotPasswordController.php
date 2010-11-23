<?php
/**
 * The default forgot password controller.
 *
 * @package    PiePHP
 * @since      Version 0.0.4
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */
class ForgotPasswordController extends Controller {

	protected $url;

	/**
	 * Initialize the controller
	 * @return null
	 */
	public function __construct()
	{
		$this->url = $GLOBALS['HTTPS_ROOT'] . 'forgot_password/';
	}

	/**
	 * Process a sign in if data has been posted.
	 * If processing does not redirect us somewhere, then show the sign in page.
	 * @return null
	 */
	public function defaultAction() {
		if (count($_POST)) {
			$this->reset();
		}
		// We must pass a title because controllers that require authorization
		// will call it and then exit.  So the dispatcher's output decorating
		// won't be done.
		$this->render(array(
			'title' => 'Forgot your password?',
			'url'   => $this->url,
		));
	}

	/**
	 * Process a request to reset the user's password
	 */
	public function reset() {
		$session = new Session();
		$session->end();
		$username = addslashes($_POST['username']);
		$email = addslashes($_POST['email']);
		$this->loadModel();
		$query = <<<HEREDOC
			id, email, username, password
			FROM users
			WHERE username = "$username" AND email = "$email"
HEREDOC;

		if ($user = $this->model->selectAssoc($query)) {
			// We found the record... proceed with resetting their password.
			$password = Authentication::generatePassword();
			$this->emailUserPassword($user, $password);
			$this->setUserPassword($user, $password);
			$this->notifyConfirmation(
				'Your reset password has been sent to your email address.'
			);
		}
		else
		{
			$this->notifyError('Incorrect username or email address.');
		}
	}

	/**
	 * Emails a copy of the User's new password to their email
	 * @param string $user     The User (an associated-array database result)
	 * @param string $password The plain text password
	 * @return null
	 *
	 * @todo properly support mail configuration settings ("From:", etc)
	 */
	protected function emailUserPassword($user, $password)
	{
		$subject = 'Password changed on ' . $GLOBALS['HTTPS_ROOT'];
		$message = <<<HEREDOC
Per your request, the password of your account on {$GLOBALS['HTTPS_ROOT']} has been changed.  You can now login to with the following password:

Password:  $password

If you did NOT request your password to be reset, please contact us immediately.
HEREDOC;
		$headers = null;
		if (false !== strpos($GLOBALS['AUTHOR'], '>'))
		{
			$headers = 'From: '.trim($GLOBALS['AUTHOR']);
		}
		mail($user['email'], $subject, $message, $headers);
	}

	/**
	 * Sets a password on a User in the database
	 * @param string $user     The User (an associated-array database result)
	 * @param string $password The plain text password
	 * @return null
	 */
	protected function setUserPassword($user, $password)
	{
		$hash = Authentication::hashPasswordSalt($password, $user['id']);
		$values = array('password' => $hash);
		$this->model->update('users', $values, $user['id']);
	}

}
