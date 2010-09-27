<?php
/**
 * A PasswordField consists of the old password if necessary, plus the new password and a confirmation password.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class PasswordField extends MultiField {

	/**
	 * A password should be at least 4 characters long.
	 */
	public $minlength = 4;

	/**
	 * A password shouldn't be more than 32 characters.
	 */
	public $maxlength = 32;

	/**
	 * The new password is required.
	 */
	public $newPassword;

	/**
	 * A confirmation password pretty much ensures that the user hasn't mis-typed the new password.
	 */
	public $confirmPassword;

	/**
	 * Initialize the field by creating its component fields.
	 * @param  $settings: the associative array of settings, used by the parent constructor.
	 * @param  $scaffold: the scaffold to which this field belongs, used by the parent constructor.
	 */
	public function __construct($settings, $scaffold) {

		$this->fields = array();
		$required = $settings['required'] && $scaffold->action == 'add';

		if ($scaffold->action == 'change') {

			$this->fields[] = $this->currentPassword = new Field(array(
				'name' => 'current_' . $settings['name'],
				'type' => 'Password',
				'cssClass' => 'currentPassword password',
				'advice' => 'To change your password, you must provide your password.',
				'required' => $required
			), $scaffold);

		}

		$this->fields[] = $this->newPassword = new Field(array(
			'name' => 'new_' . $settings['name'],
			'type' => 'Password',
			'cssClass' => 'newPassword password',
			'required' => $required,
			'label' => $scaffold->action == 'add' ? 'Choose a password' : 'New password'
		), $scaffold);

		if ($scaffold->action == 'add' || $scaffold->action == 'change') {

			$this->fields[] = $this->confirmPassword = new Field(array(
				'name' => 'confirm_' . $settings['name'],
				'type' => 'Password',
				'cssClass' => 'confirmPassword password',
				'advice' => 'Please type the password again for confirmation.',
				'required' => $required,
				'label' => 'Re-type password'
			), $scaffold);
		}

		parent::__construct($settings, $scaffold);

	}

	/**
	 * Return a hash of the password using a result ID as salt to prevent matching passwords from having matching hashes.
	 * @param  $password: the password to hash.
	 * @return the salted password hash.
	 */
	public function hash($password) {
		return md5($password . $this->scaffold->id);
	}

	/**
	 * Set the new password value on the scaffold for processing in the Model.
	 */
	public function setColumnValueOnScaffold() {
		$value = $this->newPassword->getValue();
		if ($value) {
			$this->scaffold->columnValues[$this->column] = $this->hash($value);
		}
	}

	/**
	 * Compare the password and confirmation password, and verify the old password if necessary.
	 * @return true if everything matches.
	 */
	public function isValid() {
		// Whether we're adding or changing, the newPassword value must match the confirmPassword value.
		$isValid = ($this->newPassword->getValue() == $this->confirmPassword->getValue());

		// If we're changing a password and we're not an administrator, the current password is required.
		if ($this->scaffold->action == 'change' && $this->newPassword->getValue()) {
			$hash = $this->hash($this->currentPassword->getValue());
			$sql = $this->column . '
				FROM ' . $this->scaffold->table . '
				WHERE id = ' . $this->scaffold->id;
			$result = $this->scaffold->model->selectAssoc($sql);
			if ($hash != $result[$this->column]) {
				$isValid = false;
				$this->currentPassword->hasValidationErrors = true;
				$this->currentPassword->advice = 'The password was incorrect.';
			}
		}
		return $isValid;
	}

	/**
	 * If we have just added a record, we need to set the password hash using the new record's ID as salt.
	 */
	public function processAfterScaffold() {
		if ($scaffold->action == 'add') {
			$this->scaffold->columnValues = array();
			$this->setColumnValueOnScaffold();
			$this->scaffold->model->update($this->table, $this->scaffold->columnValues);
		}
	}

}