<?php
/**
 * The sign up page for PiePHP.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2007-2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class SignUpController extends Controller {

	/**
	 * Turn caching on.
	 */
	public $useCaching = true;

	/**
	 * Show the sign up page.
	 */
	public function defaultAction() {
		$this->render(array(
      'title' => 'Sign up'
    ));
	}
}
