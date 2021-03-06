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

class DefaultController extends CachingController {

	/**
	 * Don't use a content template.  The content layout is all in the view.
	 */
	public $contentTemplateName = NULL;

	/**
	 * Show the home page.
	 */
	public function defaultAction() {
		$this->render(array(
			'title' => 'PiePHP - The instant gratification framework'
		));
	}

}
