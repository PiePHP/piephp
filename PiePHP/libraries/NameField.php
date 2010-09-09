<?php
/**
 * A simple text field for first names, last names, etc.
 * The Field class is sufficient for most of what we want here.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class NameField extends Field {

	/**
	 * A name shouldn't really be longer than 50 characters.
	 */
	public $maxlength = 50;

}