<?php

class FirstAndLastNameField extends MultiField {

	function __construct($settings) {

		parent::__construct($settings);

		$firstNameSettings = array(
			'name' => 'first_' . $settings['name'],
			'type' => 'Name'
		);
		$firstName = new NameField($firstNameSettings);

		$lastNameSettings = array(
			'name' => 'last_' . $settings['name'],
			'type' => 'Name'
		);
		$lastName = new NameField($lastNameSettings);
		
		$this->fields = array($firstName, $lastName);
	}

}