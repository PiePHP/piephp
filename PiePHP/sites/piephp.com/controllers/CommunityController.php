<?php

class CommunityController extends Controller {

	function index() {
		$data = array(
			'title' => 'Community'
		);
		$this->renderView('community/community', $data);
	}
}
