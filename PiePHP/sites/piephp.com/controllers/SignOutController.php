<?php
/**
 * Facilitate signing users out.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2007-2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class SignOutController extends NonCachingController {

	/**
	 * Sign a user out.
	 */
	public function indexAction() {
		$session = new Session();
		$session->end();
		$this->renderView('sign_out', array('title' => 'Signed out'));
	}

}
