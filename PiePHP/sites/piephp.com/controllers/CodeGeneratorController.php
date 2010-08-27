<?php

class CodeGeneratorController extends Controller {

	function indexAction() {
		$data = array(
			'title' => 'Code generator';
		);
		$this->renderView('code_generator/code_generator', $data);
	}
}
