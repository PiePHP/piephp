<?php

class AdminController extends Controller {

	function index($section = '', $action = '') {
		if ($section) {
			$scaffold_name = upper_camel($section) . 'Scaffold';
			if (class_exists($scaffold_name, true)) {
				$scaffold = new $scaffold_name();
				$scaffold->renderForm();
				$data = ob_get_clean();
				$this->renderView('echo', $data);
			}
			else {
				die('invalid admin section');
			}
		}
		die('index');
	}

}
