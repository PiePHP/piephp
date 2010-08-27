<?php

class CommunityController extends Controller {

	function indexAction() {
		$data = array(
			'title' => 'Community'
		);
		$this->renderView('community/community', $data);
	}
}
