<?php
/**
 * Discussion forums section.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2007-2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class ForumsController extends CachingController {

	/**
	 * Show the forums.
	 */
	public function indexAction() {
		//$forum = new ForumScaffold();
		//$this->loadModel('forumsModel');
		$data = array(
			'title' => 'Forums',
			//'forums' => $this->forumsModel->forums()
		);
		$this->renderView('forums/forums', $data);
	}
}
