<?php

class PasswordField extends MultiField {

	public $minlength = 4;

	public $maxlength = 32;

	public $currentPassword;

	public $newPassword;

	public $confirmPassword;

	public function __construct($settings, $scaffold) {

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

	public function hash($password) {
		return md5($password . $this->scaffold->id);
	}

	public function setColumnValueOnScaffold() {
		$value = $this->newPassword->getValue();
		if ($value) {
			$this->scaffold->columnValues[$this->column] = $this->hash($value);
		}
	}

	public function isValid() {
		// Whether we're adding or changing, the newPassword value must match the confirmPassword value.
		$isValid = ($this->newPassword->getValue() == $this->confirmPassword->getValue());

		// If we're changing a password and we're not an administrator, the current password is required.
		if ($this->scaffold->action == 'change') {
			$hash = $this->hash($this->currentPassword->getValue());
			$sql = '
				SELECT ' . $this->column . '
				FROM ' . $this->scaffold->table . '
				WHERE id = ' . $this->scaffold->id;
			$result = $this->scaffold->model->result($sql);
			/*if ($hash != $result[$this->column]) {
				$isValid = false;
				$this->currentPassword->hasValidationErrors = true;
				$this->currentPassword->advice = 'The password was incorrect.';
			}*/
		}
		return $isValid;
	}

	public function processAfterScaffold() {
		$this->scaffold->columnValues = array();
		$this->setColumnValueOnScaffold();
		$this->model->update($this->table, $this->scaffold->columnValues);
	}

}