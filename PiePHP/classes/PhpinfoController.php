<?php
/**
 * Show PHP info output when an administrator goes to /phpinfo.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class PhpinfoController extends Controller {

	/**
	 * Show PHP info for developers.
	 */
	public function defaultAction() {
		if ($GLOBALS['ENVIRONMENT'] != 'development') {
			$this->authorize(array(
				1, // System administrators
				2, // Developers
			));
		}
		phpinfo();
	}
}
