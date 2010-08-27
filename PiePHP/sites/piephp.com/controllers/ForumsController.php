<?php

class ForumsController extends Controller {

	function indexAction() {
		//$forum = new ForumScaffold();
		//$this->loadModel('forumsModel');
		$data = array(
			'title' => 'Forums',
			//'forums' => $this->forumsModel->forums()
		);
		$this->renderView('forums/forums', $data);
	}
}
