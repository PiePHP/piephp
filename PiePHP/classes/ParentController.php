<?php
/**
 * Invoke a child controller corresponding to a URL parameter.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

abstract class ParentController extends Controller {

	/**
	 * Invoke a child controller.
	 */
	public function catchAllAction() {
		global $PARAMETERS;
		global $CONTROLLER_NAME;
		global $ACTION_NAME;

		// If this is the StoreController for example, its child controllers will be in the Store directory.
		$directoryName = substr($CONTROLLER_NAME, 0, -10);

		$CONTROLLER_NAME = $directoryName . '_' . upper_camel($PARAMETERS[0]) . 'Controller';

		// If the URL was "/" or if it was "/hello/" and there's no HelloController, we'll just use the
		// DefaultController.  In the "/hello/" case, there's a chace that DefaultController has a helloAction
		// or a catchAllAction.
		if (!class_exists($CONTROLLER_NAME, true)) {
			$CONTROLLER_NAME = 'DefaultController';
		}
		else {
			array_shift($PARAMETERS);
		}
		$CONTROLLER = new $CONTROLLER_NAME();

		$ACTION_NAME = (count($PARAMETERS) ? lower_camel($PARAMETERS[0]) : '') . 'Action';

		// If the URL was "/something/hello/" then we want the helloAction of the SomethingController.  If
		// the SomethingController doesn't have a helloAction, it might have a catchAllAction.  If not,
		// then the base Controller catchAllAction method can return a 404.
		if ($ACTION_NAME == 'Action') {
			$ACTION_NAME = 'defaultAction';
		}
		if (!method_exists($CONTROLLER, $ACTION_NAME)) {
			$ACTION_NAME = 'catchAllAction';
		}
		else {
			array_shift($PARAMETERS);
		}

		call_user_func_array(array(&$CONTROLLER, $ACTION_NAME), $PARAMETERS);
	}

}
