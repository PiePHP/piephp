<?php

class HomeController extends Controller {

	public function indexAction() {
		$this->loadModel('blogModel');
		$data = array(
			'title' => 'Blog',
			'posts' => $this->blogModel->posts()
		);
		$this->renderView('home', $data);
	}
}
