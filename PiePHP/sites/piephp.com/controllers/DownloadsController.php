<?php

class DownloadsController extends Controller {

	function index() {
		$data = array(
			'title' => 'Downloads'
		);
		$this->renderView('downloads/downloads', $data);
	}
}
