<?php
/**
 * A controller in which caching is turned off.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2007-2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class NonCachingController extends Controller {

	/**
	 * Turn caching off.
	 */
	public $isCacheable = false;

}
