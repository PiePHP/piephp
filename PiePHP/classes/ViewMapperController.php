<?php
/**
 * The ViewMapperController uses the catchAllAction to map URLs to views and render them.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class ViewMapperController extends CachingController {

	/**
	 * Find a view, and render it.
	 */
	public function catchAllAction() {
    global $CONTROLLER_NAME;
    $viewName = substr(lower_camel($CONTROLLER_NAME), 0, -10);
    $arguments = func_get_args();
    if (!count($arguments)) {
      $arguments = array('default');
    }
    foreach ($arguments as $argument) {
      $viewName .= '_' . lower_camel($argument);
    }
    $this->renderView($viewName);
	}

}
