<?php
/**
 * The section of the site that is intended to get people involved in using and contributing to PiePHP.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class CommunityController extends CachingController {

	/**
	 * Show the PiePHP community homepage.
	 */
	public function defaultAction() {
		$this->render(array(
			'title' => 'Community'
		));
	}
}
