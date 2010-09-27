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

class HomeController extends CachingController {

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

	/**
	 * If the URI is "/something.js" or "/something.css", use the JsController or CssController.
	 * Otherwise, do what we do with URLs that we don't recognize.
	 * @param  $file: the name of the file, which might be "something.js" or "something.css"
	 */
	public function catchAllAction($file) {
		global $CONTROLLER;
		if (substr($file, -3) == '.js') {
			$CONTROLLER = new JsController();
			$CONTROLLER->catchAllAction($file);
		}
		else if (substr($file, -4) == '.css') {
			$CONTROLLER = new CssController();
			$CONTROLLER->catchAllAction($file);
		}
		else {
			parent::catchAllAction();
		}
	}

}
