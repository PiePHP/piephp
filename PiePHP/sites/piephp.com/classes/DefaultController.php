<?php
/**
 * The PiePHP home page.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class DefaultController extends BaseDefaultController {

	/**
	 * Show the home page.
	 */
	public function defaultAction() {
		$this->loadModel('blogModel');
		$this->render(array(
			'title' => 'PiePHP - The instant gratification framework',
			'manualContentLayout' => true,
			'posts' => $this->blogModel->posts()
		));
	}

}
