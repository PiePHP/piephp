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

class PhpinfoController extends NonCachingController {

	/**
	 * Show PHP info for developers.
	 * TODO: Check user credentials.
	 */
	public function indexAction() {
		phpinfo();
	}
}
