<?php
/**
 * The section of the site that facilitates PiePHP downloads.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class DownloadsController extends CachingController {

	/**
	 * Show the main downloads page.
	 */
	public function defaultAction() {
		$this->render(array(
			'title' => 'Downloads'
		));
	}
}
