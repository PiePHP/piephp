<?php

class AdminController extends Controller {

	function index($section = '', $action = '', $id = 0) {
		if ($section) {
			$section_camel = upper_camel($section);
			$scaffold_name = $section_camel . 'Scaffold';
			if (class_exists($scaffold_name, true)) {
				$scaffold = new $scaffold_name($section_camel, $action, $id);
				$data = array(
					'title' => $scaffold->getTitle(),
					'section' => $section,
					'scaffold' => $scaffold
				);
				if ($action == 'add' || $action == 'change') {
					return $this->renderView('admin/form', $data);
				}
				else {
					return $this->renderView('admin/list', $data);
				}
			}
		}
		$this->renderView('admin/admin');
	}

}
