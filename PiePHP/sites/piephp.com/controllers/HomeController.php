<?php

class HomeController extends Controller {

	function index() {
		$this->loadModel('blogModel');
		$data = array(
			'title' => 'Blog',
			'posts' => $this->blogModel->posts()
		);
		$this->renderView('home', $data);
	}
}
