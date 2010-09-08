<?php

class DocumentationController extends Controller {

	public function indexAction() {
		$data = array(
			'title' => 'Documentation'
		);
		$this->renderView('documentation/documentation', $data);
	}
}
