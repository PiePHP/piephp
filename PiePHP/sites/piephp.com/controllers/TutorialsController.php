<?php

class TutorialsController extends Controller {

	public function indexAction() {
		$data = array(
			'title' => 'Tutorials'
		);
		$this->renderView('tutorials/tutorials', $data);
	}
}
