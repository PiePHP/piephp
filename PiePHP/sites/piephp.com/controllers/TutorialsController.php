<?php

class TutorialsController extends Controller {

	function indexAction() {
		$data = array(
			'title' => 'Tutorials'
		);
		$this->renderView('tutorials/tutorials', $data);
	}
}
