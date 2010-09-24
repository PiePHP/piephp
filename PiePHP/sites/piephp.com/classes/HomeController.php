<?php
/**
 * The PiePHP home page.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2007-2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class HomeController extends Controller {

	/**
	 * Turn caching on.
	 */
	public $useCaching = true;

	/**
	 * Show the home page.
	 */
	public function defaultAction() {
		$this->loadModel('blogModel');
		$this->render(array(
			'title' => 'The instant gratification framework',
			'manualContentLayout' => true,
			'posts' => $this->blogModel->posts()
		));
	}
}
