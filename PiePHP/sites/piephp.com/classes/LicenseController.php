<?php
/**
 * Display the PiePHP license.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2007-2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class LicenseController extends Controller {

	/**
	 * Turn caching on.
	 */
	public $useCaching = true;

	/**
	 * Show the PiePHP license.
	 */
	public function indexAction() {
		$this->renderView('license/license', array(
      'title' => 'License'
    ));
	}

}
