<?php
/**
 * A user interface that will allow scaffolds to be created from a browser.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class CodeGeneratorController extends Controller {

	/**
	 * Show the main options for code generation.
	 */
	public function defaultAction() {
		$this->render();
	}
}
