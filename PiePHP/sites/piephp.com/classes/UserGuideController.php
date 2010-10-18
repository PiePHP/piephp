<?php
/**
 * The User guide for PiePHP.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class UserGuideController extends ViewMapperController {

	/**
	 * Use a content templated with a sub-navigation for the user guide.
	 */
	public $contentTemplateName = 'userGuideContent';

	/**
	 * Show the User Guide's table of contents (which doesn't need the menu from the userGuideContentTemplate).
	 */
	public function defaultAction() {
		$this->contentTemplateName = 'defaultContent';
		$this->render();
	}
}
