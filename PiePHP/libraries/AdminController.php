<?php
/**
 * Handle all admin URLs by using scaffolds for view/add/change/remove operations.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class AdminController extends NonCachingController {

	/**
	 * Show a list of links to available admin sections.
	 */
	public function indexAction() {
		$this->authenticate();
		$data = array('title' => 'Admin');
		$this->renderView('admin/admin', $data);
	}

	/**
	 * Use a scaffold to show an admin page.
	 * For example, "/admin/users/change/3" would show the page that allows you to edit user #3.
	 * @param  $sectionName: the name of the scaffold.
	 * @param  $action: the action the scaffold will perform e.g. "add", "change", "remove".
	 * @param  $id: the ID of the record on which the scaffold will operate.
	 */
	public function catchAllAction($sectionName = '', $action = '', $id = 0) {
		$this->authenticate();
		$sectionNameCamel = upper_camel($sectionName);
		$scaffoldName = $sectionNameCamel . 'Scaffold';
		if (class_exists($scaffoldName, true)) {
			$scaffold = new $scaffoldName($sectionNameCamel, $action, $id);
			$data = array(
				'title' => $scaffold->getTitle(),
				'section' => $sectionName,
				'scaffold' => $scaffold
			);
			$scaffold->processPost();
			return $this->renderView('admin/' . $scaffold->action, $data);
		}
	}

}
