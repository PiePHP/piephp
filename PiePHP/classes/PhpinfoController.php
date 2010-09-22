<?php
/**
 * Show PHP info output when an administrator goes to /phpinfo.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2007-2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class PhpinfoController extends Controller {

	/**
	 * Show PHP info for developers.
	 * TODO: Check user credentials for "developer" status or greater before showing them server configuration info.
	 */
	public function indexAction() {
		if ($GLOBALS['ENVIRONMENT'] != 'development') {
			$this->authenticate();
		}
		phpinfo();
	}
}
