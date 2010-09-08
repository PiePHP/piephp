<?php

class FirstAndLastNameField extends MultiField {

	public $inline = true;

	public function __construct($settings, $scaffold) {

		parent::__construct($settings, $scaffold);

		$firstNameSettings = array(
			'name' => 'first_' . $settings['name'],
			'type' => 'Name',
			'required' => $this->required,
			'hint' => $this->hint ? $this->hint[0] : 'First name'
		);
		$firstName = new NameField($firstNameSettings, $scaffold);

		$lastNameSettings = array(
			'name' => 'last_' . $settings['name'],
			'type' => 'Name',
			'required' => $this->required,
			'hint' => $this->hint ? $this->hint[0] : 'Last name'
		);
		$lastName = new NameField($lastNameSettings, $scaffold);

		$this->fields = array($firstName, $lastName);
	}

}