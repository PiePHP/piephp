<?php
/**
 * Search for database patches in models/database_patches, and run any that are found.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2007-2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class DatabasePatchesController extends NonCachingController {

	/**
	 * Search for database patches in models/database_patches, and run any that are found.
	 */
	public function indexAction() {
		$this->authenticate();
		$data = array('title' => 'Admin');
		$this->renderView('admin/admin', $data);
	}

}
