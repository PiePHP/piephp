<?php
/**
 * The CachingController's subclasses will have caching turned on by default.
 * It can still be turned off within a method.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2007-2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class CachingController extends Controller {

	/**
	 * Turn caching on.
	 */
	protected $useCaching = true;

}
