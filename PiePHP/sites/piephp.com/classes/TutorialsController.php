<?php
/**
 * PiePHP tutorials.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class TutorialsController extends CachingController {

	/**
	 * Show the list of tutorials.
	 */
	public function defaultAction() {
		$this->render(array(
			'title' => 'Tutorials'
		));
	}
}
