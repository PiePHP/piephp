<?php

class AdminController extends Controller {
	
	public $isCacheable = false;

	function indexAction($section = '', $action = '', $id = 0) {
		$data = array('title' => 'Admin');
		$this->renderView('admin/admin', $data);
	}

	function catchAllAction($section = '', $action = '', $id = 0) {
		$sectionCamel = upper_camel($section);
		$scaffoldName = $sectionCamel . 'Scaffold';
		if (class_exists($scaffoldName, true)) {
			$scaffold = new $scaffoldName($sectionCamel, $action, $id);
			$data = array(
				'title' => $scaffold->getTitle(),
				'section' => $section,
				'scaffold' => $scaffold
			);
			$scaffold->processPost();
			return $this->renderView('admin/' . $scaffold->action, $data);
		}
	}

}
