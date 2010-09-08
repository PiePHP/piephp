<?php

class CommunityController extends Controller {

	public function indexAction() {
		$data = array(
			'title' => 'Community'
		);
		$this->renderView('community/community', $data);
	}
}
