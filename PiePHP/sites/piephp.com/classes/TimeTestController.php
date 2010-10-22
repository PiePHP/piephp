<?php
/**
 * A timing test to compare a simple request for 3 blog posts between several frameworks.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class TimeTestController extends CachingController {

	/**
	 * Don't use a content template.  The content layout is all in the view.
	 */
	public $contentTemplateName = NULL;

	/**
	 * Show the home page.
	 */
	public function defaultAction() {
		$this->loadModel('blogModel');
		$this->renderView('timeTest_default', array(
			'title' => 'PiePHP - The instant gratification framework',
			'posts' => $this->blogModel->posts()
		));
	}

}
