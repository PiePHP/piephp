<?php

class PasswordField extends MultiField {

	public $minlength = 4;

	public $maxlength = 32;

	public $currentPassword;

	public $newPassword;

	public $confirmPassword;

	function __construct($settings, $scaffold) {

		$this->fields = array();

		if ($scaffold->action == 'change') {

			$this->fields[] = $this->currentPassword = new Field(array(
				'name' => 'current_' . $settings['name'],
				'type' => 'Password',
				'advice' => 'Please enter the password to change it.',
				'required' => false
			), $scaffold);

		}

		$this->fields[] = $this->newPassword = new Field(array(
			'name' => 'new_' . $settings['name'],
			'type' => 'Password',
			'required' => $this->required,
			'label' => $scaffold->action == 'add' ? 'Choose a password' : 'New password'
		), $scaffold);

		if ($scaffold->action == 'add' || $scaffold->action == 'change') {

			$this->fields[] = $this->confirmPassword = new Field(array(
				'name' => 'confirm_' . $settings['name'],
				'type' => 'Password',
				'required' => false,
				'label' => 'Re-type password'
			), $scaffold);
		}

		parent::__construct($settings, $scaffold);

	}

	function setColumnValue(&$columnValues) {
		if ($this->newPassword->getValue()) {
			$columnValues[$this->column] = $this->newPassword->getValue();
		}
	}

	function isValid() {
		// Whether we're adding or changing, the newPassword value must match the confirmPassword value.
		$isValid = ($this->newPassword->getValue() == $this->confirmPassword->getValue());

		// If we're changing a password and we're not an administrator, the current password is required.
		if ($this->scaffold->action == 'change') {
			$hash = md5($this->currentPassword->getValue() . $this->scaffold->id);
			$sql = '
				SELECT ' . $this->currentPassword->name . '
				FROM ' . $this->scaffold->table . '
				WHERE id = ' . $this->scaffold->id;
			$result = $this->scaffold->model->result($sql);
			if ($hash != $result[$this->currentPassword->name]) {
				$isValid = false;
			}
		}
		return $isValid;
	}

	function processAfterScaffold() {
		if ($this->currentPassword->getValue()) {
			$hash = md5($this->currentPassword->getValue() . $this->scaffold->id);
			$sql = '
				UPDATE ' . $this->scaffold->table . '
				SET ' . $this->currentPassword->column . " = '$hash'" . '
				WHERE id = ' . $this->scaffold->id;
			$this->scaffold->model->execute($sql);
		}
	}

}