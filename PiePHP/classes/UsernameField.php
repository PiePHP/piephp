<?php
/**
 * A UsernameField is used to edit usernames in a Scaffold.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class UsernameField extends Field {

	/**
	 * The maximum length of a username is 32 characters.
	 */
	public $maxlength = 32;

	/**
	 * The default advice for usernames must be specific to the accepted format for usernames.
	 * TODO: Create the server-side validation for usernames.
	 * @return the validation advice message as a string.
	 */
	public function getDefaultAdvice() {
		return parent::getDefaultAdvice() . '<br>It can only have letters, numbers and ".", "-" or "_".';
	}

}