<?php
/**
 * Facilitate signing users out.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class SignOutController extends Controller {

	/**
	 * Sign a user out.
	 */
	public function defaultAction() {
		$session = new Session();
		$session->end();
		$this->render(array(
			'title' => 'Signed out'
		));
	}

}
