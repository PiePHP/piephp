<?php

class DocumentationController extends Controller {

	function indexAction() {
		$data = array(
			'title' => 'Documentation'
		);
		$this->renderView('documentation/documentation', $data);
	}
}
