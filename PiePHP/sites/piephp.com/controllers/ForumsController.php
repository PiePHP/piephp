<?php

class ForumsController extends Controller {

	function index() {
		$forum = new ForumScaffold();
		$this->loadModel('forumsModel');
		$data = array(
			'title' => 'Forums',
			'forums' => $this->forumsModel->forums()
		);
		$this->renderView('forums/forums', $data);
	}
}
