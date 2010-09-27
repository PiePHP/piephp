<?php
/**
 * The API documentation for PiePHP.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class DocumentationController extends CachingController {

	/**
	 * Show the table of contents.
	 */
	public function defaultAction() {
		$this->render(array(
			'title' => 'Documentation'
		));
	}
}
