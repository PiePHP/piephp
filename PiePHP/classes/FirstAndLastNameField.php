<?php
/**
 * Render a first name and a last name side-by-side in a form or a data table.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class FirstAndLastNameField extends MultiField {

	/**
	 * First name and last name will be next to each other, so their MultiField layout must be inline.
	 */
	public $inline = true;

	/**
	 * Create the two fields and populate this MultiField's fields array.
	 * @param  $settings:
	 * @param  $scaffold:
	 */
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