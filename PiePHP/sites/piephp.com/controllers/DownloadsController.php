<?php

class DownloadsController extends Controller {

	function indexAction() {
		$data = array(
			'title' => 'Downloads'
		);
		$this->renderView('downloads/downloads', $data);
	}
}
