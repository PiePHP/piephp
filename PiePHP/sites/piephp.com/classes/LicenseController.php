<?php
/**
 * Display the PiePHP license.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class LicenseController extends CachingController {

	/**
	 * Show the PiePHP license.
	 */
	public function defaultAction() {
		$this->render(array(
			'title' => 'License'
		));
	}

}
