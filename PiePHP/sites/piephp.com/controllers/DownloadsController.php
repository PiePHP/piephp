<?php

class DownloadsController extends Controller {

	public function indexAction() {
		$data = array(
			'title' => 'Downloads'
		);
		$this->renderView('downloads/downloads', $data);
	}
}
