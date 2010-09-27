<?php
/**
 * This is just a controller with caching turned on.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

abstract class CachingController extends Controller {

	/**
	 * Turn caching on.
	 */
	public $useCaching = true;

}
