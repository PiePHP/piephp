<?php
/**
 * Display and validate an email address field in a scaffold.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2007-2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class EmailField extends Field {

	/**
	 * Maximum length of an email address
	 */
	public $maxlength = 254;

	/**
	 * Validate an email address.
	 * @return true if a valid email address has been entered.
	 */
	public function isValid() {
		return preg_match('/^(?!\.)([a-zA-Z0-9_\.\-#\$%\*\/\?\|\^\{\}`~&\'\+\-=_])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/', $this->getValue());
	}
}